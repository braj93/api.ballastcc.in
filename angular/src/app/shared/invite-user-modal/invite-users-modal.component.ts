import { Output, Input, Component, OnInit, ViewChild, Inject, Renderer2, EventEmitter, TemplateRef } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { FormBuilder, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Subject } from 'rxjs/Subject'
import { UserService, AdminService } from '../../core';
@Component({
  selector: 'app-invite-users-modal',
  templateUrl: './invite-users-modal.component.html',
  styleUrls: ['./invite-users-modal.component.css']
})

export class InviteUsersModalComponent implements OnInit {
  public song_name : any;
  public artist_name : any;
  public emails = [];
  public alerts: any = [];
  public isSubmitting = false;
  public inviteUsersForm: FormGroup;
  
  public onClose: Subject<boolean>;
  public modalRef: BsModalRef;
  public config = {
    backdrop: true,
    ignoreBackdropClick: false
  };
  public plans: any = [];
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

    
    this.inviteUsersForm = this.fb.group({
      'user_email': [''],
      'agency_id': [this.userService.param_id, [Validators.required]],
      'is_agree': ['', [Validators.required]],
      'plan_id': [null, [Validators.required]],
      'user_id': [this.userService.param_id]
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
    this.getPlans();
  }

  getUsersList(){
    let user_id_control = this.inviteUsersForm.get('user_id');
    user_id_control.setValidators([Validators.required]);
    user_id_control.updateValueAndValidity();
    this.userService.getUserList({type:'AGENCY'}).subscribe((response: any) => {
      this.user_list = response.data;
    }, err => {
      console.log(err.message);
    });
  }

  submitForm(){
    // console.log("this.inviteUsersForm.value", this.inviteUsersForm.value);
    this.isSubmitting = true;
    this.userService.inviteUsers(this.inviteUsersForm.value).subscribe((response: any) => {
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

  getPlans(){
    // this.adminService.plansList().subscribe((response: any) => {
    this.adminService.getPlansByType({type: 'NON_AGENCY'}).subscribe((response: any) => {
      this.plans = response.data
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  
  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

}