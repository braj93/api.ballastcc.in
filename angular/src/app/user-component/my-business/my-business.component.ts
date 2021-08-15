import {Component, OnInit} from '@angular/core';
import { UserService } from '../../core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { InviteUsersModalComponent } from '../../shared';
import {ActivatedRoute} from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-my-business',
  templateUrl: './my-business.component.html',
  styleUrls: ['./my-business.component.css']
})
export class MyBusinessComponent implements OnInit{
  public modalRef: BsModalRef;
  public config_invite_users = {
    backdrop: true,
    ignoreBackdropClick: true,
    class : 'modal-md'
  };
  public isSubmitting: boolean = false;
  public alerts: any = [];
  public rows: any = [];
  public loading: boolean = false;
  public counts: any;
  public page_no: any = 0;
  public currentUser: any = '';
  public parameters:any = {
    agency_id: "",
    keyword: "",
    pagination: {
        offset: 0,
        limit: 10
      },
    sort_by:{
      column_name: '',
      order_by: '',
    },
    filter:""
  };
  public param_id: any;
  constructor(
      public location: Location,
      public userService: UserService,
      public modalService: BsModalService,
      public route: ActivatedRoute,
    ){

  }

  ngOnInit(): void {
    this.userService.currentUser.subscribe(
      (userData) => {
        this.currentUser = userData;
      }
    );
    if(this.currentUser.user_type === 'OWNER' || this.currentUser.user_type === 'ADMIN'){
      this.userService.page_title = this.userService.my_title+" Businesses";
    }else{
      this.userService.page_title = "My Businesses";
    }
    
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.parameters.agency_id = this.param_id;
    this.getInvitedUsersList();
  }

  setPage(event) {
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.userService.invitedUsersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  onSort(event){
    this.page_no = event.offset;
    this.parameters.sort_by.column_name = event.sorts[0].prop == 'name' ? 'first_name' : event.sorts[0].prop;
    this.parameters.sort_by.order_by = event.sorts[0].dir;
    // console.log("this.parameters", this.parameters);
    this.userService.invitedUsersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }



  getInvitedUsersList(){
    // console.log(this.parameters, " -- --- -- params");
    this.userService.invitedUsersList(this.parameters).subscribe((response: any) => {
      this.isSubmitting = true; 
      this.rows = response.data;
      this.counts = response.counts;
      console.log("this.rows", this.rows);
      this.isSubmitting = false;
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.isSubmitting = false;
    });
  }

  openInviteUsersModal(){
      this.userService.param_id = this.param_id;
      this.modalRef = this.modalService.show(InviteUsersModalComponent, this.config_invite_users);
      this.userService.popupRef = this.modalRef;
  }

  hideModal(){
    this.userService.popupRef.hide();
  }

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

  search(event){
    if(this.parameters.keyword == "") {
      this.page_no = 0;
    }
    this.parameters.pagination.offset = 0;
    this.userService.invitedUsersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
    });
  }

  goToBack() {
    this.location.back();
  }
}
