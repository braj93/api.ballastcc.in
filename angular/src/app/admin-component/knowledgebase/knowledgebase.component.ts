import {Component, ViewChild, OnInit} from '@angular/core';
import { UserService, AdminService } from '../../core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { InviteUsersModalComponent } from '../../shared';
import {Observable} from 'rxjs';
import {map} from 'rxjs/operators';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';

declare var $;

@Component({
  selector: 'app-knowledgebase',
  templateUrl: './knowledgebase.component.html',
  styleUrls: ['./knowledgebase.component.css']
})
export class KnowledgebaseComponent implements OnInit{
  public modalRef: BsModalRef;
  public config_invite_users = {
    backdrop: true,
    ignoreBackdropClick: true,
    class : 'modal-sm custom-modal-sm'
  };
  public isSubmitting: boolean = false;
  public alerts: any = [];
  public rows: any = [];
  public loading: boolean = false;
  public counts: any;
  public page_no: any = 0;
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
      public userService: UserService,
      public adminService: AdminService,
      public modalService: BsModalService,
      public route: ActivatedRoute,
      public router: Router,
    ){

  }

  ngOnInit(): void {
    this.userService.page_title = "Knowledge Base";
      this.route.data.subscribe((data:any) => {
        let result = data['kbdata'];
        this.rows = result.data;
        this.counts = result.counts;
    });
    // this.route.params.subscribe(params => {
    //   this.param_id = params.id;
    // });
    // this.parameters.agency_id = this.param_id;
    // this.getInvitedUsersList();
  }

  setPage(event) {
    this.page_no = event.offset;
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.adminService.contentList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  onSort(event){
    this.parameters.sort_by.column_name = event.sorts[0].prop == 'contentTitle' ? 'name' : event.sorts[0].prop;
    this.parameters.sort_by.order_by = event.sorts[0].dir;
    this.adminService.contentList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }



  getInvitedUsersList(){
    this.adminService.contentList(this.parameters).subscribe((response: any) => {
      this.isSubmitting = true; 
      this.rows = response.data;
      this.counts = response.counts;
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

  delete(knowledgebase_id){
    this.isSubmitting = true;
    let input = {
      "knowledgebase_id": knowledgebase_id,
      "delete_type": "STATUS_DELETED" 
    }
    this.adminService.deleteKnowledgebase(input).subscribe((response: any) => {
       this.isSubmitting = false;
       this.userService.alerts.push({
         type: 'success',
         msg: response.message,
         timeout: 4000
       });
       this.getInvitedUsersList();
       this.router.navigate(['/console/knowledgebase']);
     },
     err => {
       this.userService.alerts.push({
         type: 'danger',
         msg: err.message,
         timeout: 4000
       });
       this.isSubmitting = false;
     })
  }

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }
  
  chageStatus(id, status, index){
    let params = {
      'knowledgebase_id': id,
      'status': status
    }
    // let index = this.userService.getIndexOfList(this.rows, 'knowledgebase_id', id);
    this.adminService.changeKnowledgebaseStatus(params).subscribe((response: any) => {
      this.rows[index].status = status;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
    },
     err => {
       this.userService.alerts.push({
         type: 'danger',
         msg: err.message,
         timeout: 4000
       });
     });
  }

  search(event){
    if(this.parameters.keyword == "") {
      this.page_no = 0;
    }
    this.parameters.pagination.offset = 0;
    this.adminService.contentList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
    });
  }
}