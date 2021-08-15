import {Component, OnInit} from '@angular/core';
import { UserService, CrmService } from '../core';
import { FormBuilder, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';
import { Location } from '@angular/common';
declare var $;

@Component({
  selector: 'app-user-profile',
  templateUrl: './user-edit-profile.component.html',
  styleUrls: ['./user-profile.component.css']
})
export class UserEditProfileComponent implements OnInit{
  public form_details : any;
  public emails = [];
  public alerts: any = [];
  public isSubmitting = false;
  public isEdit = true;
  public userForm: FormGroup;
  public param_id: any;
  public currentUser: any;
  constructor(
      public userService: UserService,
      public crmService: CrmService,
      private fb: FormBuilder,
      public router: Router,
      public route: ActivatedRoute,
      public location: Location,
    ){
    this.userForm = this.fb.group({
      'name': ['', [Validators.required]],
      'business_name': ['', [Validators.required]],
      'email': ['', [Validators.required, Validators.email]],
    });

  }

  ngOnInit(): void {
    this.userService.currentUser.subscribe(
      (userData) => {
      this.currentUser = userData;
      const user_role =  this.currentUser.user_role.replace('__', '');
      if (user_role === 'ADMIN') {
        this.isEdit = false;
      }
    });
  	this.userService.page_title = "Edit User Profile";
    this.param_id = this.route.snapshot.paramMap.get('id');
    this.userService.getDetailByUserId({"user_id": this.param_id}).subscribe((response: any) => {
      this.form_details = response.data;
      this.setForm();
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  setForm(){
    this.userForm = this.fb.group({
      'name': [this.form_details.name, [Validators.required]],
      'business_name': [this.form_details.business_name, [Validators.required]],
      'email': [this.form_details.email, [Validators.required, Validators.email]],
    });
  }

  submitForm(){
    this.userForm.value.user_id = this.param_id;
    this.isSubmitting = true;
    this.userService.updateUserDetail(this.userForm.value).subscribe((response: any) => {
      this.alerts.push({
        type: 'success',
        msg: 'Updated Successfully!',
        timeout: 4000
      });
      setTimeout(() => {
          this.isSubmitting = false;
          this.userService.alerts = [];
      }, 2000);
      this.back();

    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      setTimeout(() => {
          this.isSubmitting = false;
      }, 2000);
    });
  }

  back(){
    this.location.back();
  }
}