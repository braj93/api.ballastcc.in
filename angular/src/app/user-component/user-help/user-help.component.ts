import { Component, OnInit, ViewChild } from '@angular/core';
import { UserService } from '../../core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-user-help-profile',
  templateUrl: './user-help.component.html',
  styleUrls: ['./user-help.component.css']
})
export class UserHelpComponent implements OnInit {
  public submitForm: FormGroup;
  public isSubmitting: any = false;
  public currentUser: any = '';
  constructor(
    private location: Location,
    public userService: UserService,
    public fb: FormBuilder,
    public router: Router,
  ) { }

  ngOnInit(): void {
    this.userService.page_title = 'Get Help';
    this.userService.currentUser.subscribe(
      (userData) => {
        this.currentUser = userData;
      });
      console.log(this.currentUser);
      this.submitForm = this.fb.group({
        'business_name': [this.currentUser.business_name, [Validators.required]],
        'name': [this.currentUser.name, [Validators.required]],
        'phone': [this.currentUser.phone, [Validators.required]],
        'email': [this.currentUser.email, [Validators.required, Validators.email]],
        'message': ['', [Validators.required]]
      });
  }

  formSubmit() {
    this.isSubmitting = true;
    this.userService.sendHelpEmail(this.submitForm.value).subscribe((response: any) => {
      this.isSubmitting = false;
      // this.submitForm.reset();
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
        setTimeout(() => {
          this.isSubmitting = false;
          this.userService.alerts = [];
        }, 2000);
        this.isSubmitting = false;
      });
  }

  goToBack() {
    this.location.back();
  }
}
