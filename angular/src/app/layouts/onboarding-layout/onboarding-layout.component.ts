import { Component, OnInit } from '@angular/core';
import { FormGroup,FormControl, FormBuilder, Validators } from '@angular/forms';
// import { EmailValidation, PasswordValidation } from './match-password';
import {FormsModule,ReactiveFormsModule} from '@angular/forms';
import { AdminService } from '../../core';
// import 'rxjs/add/operator/filter';
import { Location } from '@angular/common';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';
@Component({
  selector: 'app-onboarding-layout',
  templateUrl: './onboarding-layout.component.html',
  styleUrls: ['./onboarding-layout.component.scss']
})
export class OnboardingLayoutComponent implements OnInit {
  public authForm: FormGroup;
  // public heroForm: FormGroup;
  public isSubmitting: boolean = false;
  public is_show : string = 'LOGIN';
  public code :string = '';
  currentUser: any;
  constructor(
    public location: Location,
    private router: Router,
    private route: ActivatedRoute,
    private fb: FormBuilder,
    public adminService: AdminService,
    private formBuilder: FormBuilder
  ) { 
    this.authForm = this.formBuilder.group({
      'email': ['',[Validators.required, Validators.email]],
      'device_type': ['web_browser'],
      'password': ['', [Validators.required, Validators.minLength(8)]]
    })
    }

    ngOnInit():void {
    }

    get getControl(){
      return this.authForm.controls;
    }
  

      // convenience getter for easy access to form fields
      // get f() { return this.authForm.controls; }

  submitForm() {
    this.isSubmitting = true;
    const credentials = this.authForm.value;
    // console.log(credentials);
    this.adminService
      .adminLogin(credentials)
      .subscribe(
        (data: any) => {
          this.isSubmitting = false;
          this.adminService.alerts.push({
            type: 'success',
            msg: data.message,
            timeout: 4000
          });
          if (data.data.user_type === 'USER') {
            this.router.navigate(['/user/user-dashboard']);
          } else {
            this.router.navigate(['/admin/dashboard']);
          }
        },
        err => {
          this.adminService.alerts.push({
            type: 'danger',
            msg: err.message,
            timeout: 4000
          });
          this.isSubmitting = false;
        }
      );
  }
  
  showDiv(){
    if (this.router.url === '/users/login') {
      this.is_show = 'LOGIN';
    }else if(this.router.url === '/users/signup-success') {
      this.is_show = 'SIGNUP_SUCCESS';
    }else if(this.router.url === '/users/forgot-password') {
      this.is_show = 'FORGOT_PASSORWD';
    }else if(this.router.url === '/users/set-new-password/'+this.code) {
      this.is_show = 'SET_NEW_PASSORWD';
    }else if(this.router.url === '/users/password-success') {
      this.is_show = 'PASSORWD_SUCCESS';
    } else {
      this.is_show = 'LOGIN';
    }
  }

}
