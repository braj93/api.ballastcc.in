import { Component, OnInit, ViewChild, Inject, Renderer2, EventEmitter, TemplateRef } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Subject } from 'rxjs/Subject'
import { UserService, AdminService } from '../../core';
@Component({
  selector: 'app-add-member-modal',
  templateUrl: './add-member-modal.component.html',
  styleUrls: ['./add-member-modal.component.css']
})

export class AddMemberModalComponent implements OnInit {
  public emails = [];
  public alerts: any = [];
  public isSubmitting = false;
  public submitMemberForm: FormGroup;
  public onClose: Subject<boolean>;
  public modalRef: BsModalRef;
  public is_admin: boolean = false;
  public user_list: any = [];
  public currentUser:any = '';
  constructor(
    public userService: UserService,
    public adminService: AdminService,
    private fb: FormBuilder,
    private router: Router,
    public route: ActivatedRoute,
    private modalService: BsModalService,
  ) {
    this.submitMemberForm = this.fb.group({
      'user_email': [''],
      'agency_id': [this.userService.param_id, [Validators.required]],
      'user_id': [this.userService.param_id],
    });
    this.userService.currentUser.subscribe(
      (userData) => {
      this.currentUser = userData;
    });
  }

  ngOnInit() {
    if (this.currentUser.user_type == 'ADMIN') {
      this.getUsersList();
      this.is_admin = true;
    }
  }

  getUsersList(){
    let user_id_control = this.submitMemberForm.get('user_id');
    user_id_control.setValidators([Validators.required]);
    user_id_control.updateValueAndValidity();
    this.userService.getUserList({type:'MEMBER'}).subscribe((response: any) => {
      this.user_list = response.data;
    }, err => {
      console.log(err.message);
    });
  }

  submitForm(){
    // console.log("this.submitMemberForm.value", this.submitMemberForm.value);
    let form_values:any = this.submitMemberForm.value
    if (this.currentUser.user_type == 'ADMIN') {
      form_values.agency_id = form_values.user_id
    }
    // console.log("form_values", form_values);
    // return
    this.isSubmitting = true;
    this.userService.inviteMember(form_values).subscribe((response: any) => {
      this.hideModal();
      this.userService.alerts.push({
        type: 'success',
        msg: 'Invite sent successfully.',
        timeout: 4000
      });
      setTimeout(() => {
          this.isSubmitting = false;
          this.userService.alerts = [];
      }, 2000);
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.hideModal();
      setTimeout(() => {
          this.isSubmitting = false;
      }, 2000);
    });
  }

  hideModal() {
    this.userService.popupRef.hide();
  }
  
  ngOnDestroy(): void{
    this.userService.alerts = [];
  }
}