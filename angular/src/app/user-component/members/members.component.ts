import { Component, OnInit, TemplateRef } from '@angular/core';
import { UserService } from '../../core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { AddMemberModalComponent } from '../../shared';
import {ActivatedRoute} from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Location } from '@angular/common';
@Component({
  selector: 'app-members',
  templateUrl: './members.component.html',
  styleUrls: ['./members.component.css']
})
export class MembersComponent implements OnInit {
  public modalRef: BsModalRef;
  public submitMemberForm: FormGroup;
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
    user_id: "",
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
      private fb: FormBuilder,
      public userService: UserService,
      public modalService: BsModalService,
      public route: ActivatedRoute,
    ){
      this.submitMemberForm = this.fb.group({
        'organization_member_id': [''],
        'email': ['', [Validators.required]]
      });
  }

  ngOnInit(): void {
    this.userService.currentUser.subscribe(
      (userData) => {
        this.currentUser = userData;
      }
    );
    if(this.currentUser.user_type === 'OWNER' || this.currentUser.user_type === 'ADMIN'){
      this.userService.page_title = this.userService.my_title+" Team Members";
    }else{
      this.userService.page_title = "Team Members";
    }
    
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.parameters.user_id = this.param_id;
    this.getTeamMembersList();
  }

  setPage(event) {
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.userService.getTeamMembersList(this.parameters).subscribe((response: any) => {
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
    this.userService.getTeamMembersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }



  getTeamMembersList(){
    this.userService.getTeamMembersList(this.parameters).subscribe((response: any) => {
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
      this.modalRef = this.modalService.show(AddMemberModalComponent, this.config_invite_users);
      this.userService.popupRef = this.modalRef;
      this.modalService.onHide.subscribe(result => {
        this.getTeamMembersList()
      });
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
    this.userService.getTeamMembersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
    });
  }

  chageStatus(id, status, index){
    let params = {
      'user_id': id,
      'status': status
    }
    // let index = this.userService.getIndexOfList(this.rows, 'user_guid', id);
    this.userService.changeUserStatus(params).subscribe((response: any) => {
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

  submitForm(){
    this.isSubmitting = true;
    let form_values:any = this.submitMemberForm.value;
    this.userService.sendEmailAgainTomember(form_values).subscribe((response: any) => {
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.getTeamMembersList();
      this.closeModal();
    },
     err => {
      this.isSubmitting = false;
       this.userService.alerts.push({
         type: 'danger',
         msg: err.message,
         timeout: 4000
       });
     });
  }

  sendEmailAgainTomember(row, template: TemplateRef<any>) {
    this.submitMemberForm = this.fb.group({
      'organization_member_id': [row.organization_member_guid, [Validators.required]],
      'email': [row.email, [Validators.required, Validators.email]]
    });
    this.modalRef = this.modalService.show(template, this.config_invite_users);
  }
  closeModal() {
    this.modalRef.hide();
  }
  goToBack() {
    this.location.back();
  }
}