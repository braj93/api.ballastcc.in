import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { UserService } from '../../core';
import { PasswordValidation } from './match-password';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-setnewpassword-page',
  templateUrl: './set-new-password.component.html',
  styleUrls: ['./set-new-password.component.scss']
})
export class SetNewPasswordComponent implements OnInit {
  authType: String = '';
  isSubmitting = false; 
  resetPasswordForm: FormGroup; 
  alerts: any = [];
  code:any = '';


  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private userService: UserService,
    private fb: FormBuilder
  ) {

    this.resetPasswordForm = this.fb.group({ 
      'password': ['', [Validators.required, Validators.minLength(8)]],
      'confirmPassword': ['', Validators.required]
    },{
          validator: PasswordValidation.MatchPassword // your validation method
    });
  }


  submitResetPasswordForm() {
    this.isSubmitting = true; 
    const credentials = this.resetPasswordForm.value;
    credentials.password_reset_code = this.code;
    this.userService
    .resetPassword(credentials)
    .subscribe( 
      data => {  
         this.userService.alerts.push({
          type: 'success',
          msg: data.message,
          timeout: 4000
        }); 

        setTimeout(() => {
            // this.router.navigate(['/login']);
            this.router.navigate(['/users/password-success']);     
        }, 4000);         
      },
      err => {
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
        this.isSubmitting = false;
      }
    );

  }
 ngOnInit() {
  this.code = this.route.snapshot.paramMap.get('code');
    console.log(this.code)
 }  


 ngOnDestroy(): void{
  this.userService.alerts = [];
}
}
