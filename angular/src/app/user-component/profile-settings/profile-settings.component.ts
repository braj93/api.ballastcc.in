import {Component, OnInit} from '@angular/core';
import { UserService } from '../../core';
import {ActivatedRoute, Router} from '@angular/router';
import { FormBuilder, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Location } from '@angular/common';

@Component({
  selector: 'app-profile-settings',
  templateUrl: './profile-settings.component.html',
  styleUrls: ['./profile-settings.component.css']
})
export class ProfileSettingsComponent implements OnInit{
  public isSubmitting: boolean = false;
  public alerts: any = [];
  public form_details: any;
  public userForm: FormGroup;
  public user_id: any;
  public userData: any;
  constructor(
      private location: Location,
      public userService: UserService,
      public route: ActivatedRoute,
      private fb: FormBuilder,
      public router: Router,

    ){

    this.userForm = this.fb.group({
      'name': ['', [Validators.required]],
      'business_name': ['', [Validators.required]],
      'email': ['', [Validators.required, Validators.email]],
    });

  }

  ngOnInit(): void {
    this.userService.page_title = "Profile Settings";
    this.userService.currentUser.subscribe((userData: any) => {
        this.userData = userData;
        this.user_id = userData.user_guid;
        if (this.user_id) {
          this.getUserDetail(this.user_id);
        }
      }
    );
  }

  isReadonly(){
    if (this.userData.user_role == "USER_INDIVIDUAL_TEAM") {
      return true;
    }
    return false;
  }

  getUserDetail(user_id){
    this.userService.getDetailByUserId({user_id: user_id}).subscribe((response: any) => {
      this.isSubmitting = true; 
      this.form_details = response.data;
      this.setForm();
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

  setForm(){
    this.userForm = this.fb.group({
      'name': [this.form_details.name, [Validators.required]],
      'business_name': [this.form_details.business_name, [Validators.required]],
      'email': [this.form_details.email, [Validators.required, Validators.email]],
    });
  }

  submitForm(){
    this.userForm.value.user_id = this.user_id;
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
      this.router.navigate(['/user/view-profile']);
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
  goToBack() {
    this.location.back();
  }
}
