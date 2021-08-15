import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import 'rxjs/add/operator/filter';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';
import { UserService } from '../../core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { EmailValidation, PasswordValidation } from './match-password';
import { StripeService, Elements, Element as StripeElement, ElementsOptions } from "ngx-stripe";
@Component({
  selector: 'app-onboarding-layout',
  templateUrl: './onboarding-layout.component.html',
  styleUrls: ['./onboarding-layout.component.scss']
})
export class OnboardingLayoutComponent implements OnInit {

  // STRIPE START
  elements: Elements;
  card: StripeElement;
  elementsOptions: ElementsOptions = {
    locale: 'en'
  };
  // STRIPE END
  
  public isSubmitting: boolean = false;
  alerts: any = [];
  authForm: FormGroup;
  signupForm: FormGroup;
  signupAgencyMemberForm: FormGroup;
  signupTeamMemberForm: FormGroup;
  paymentForm: FormGroup;
  forgotPasswordForm: FormGroup; 
  resetPasswordForm: FormGroup; 
  countries: any = [];
  public code :string = '';
  public pricing_plan_id :string = '';
  public is_show : string = 'LOGIN';
  public is_show_card : boolean = false;
  constructor( 
    private stripeService: StripeService,
    public location: Location,
    private router: Router,
    private route: ActivatedRoute,
    private fb: FormBuilder,
    public userService: UserService
    ) {

    this.forgotPasswordForm = this.fb.group({ 
      'email': ['']
    });
    this.route.queryParams.subscribe(params => {
      this.pricing_plan_id = params['plan_id'];
    });
    router.events.subscribe((event: Event) => {
      if (event instanceof NavigationEnd) {
        this.code = this.route.snapshot.paramMap.get('code')
        this.showDiv();
      }
    });

    this.authForm = this.fb.group({
      'email': [''],
      'device_type': ['web_browser'],
      'password': ['', [Validators.required, Validators.minLength(8)]]
    });

    this.resetPasswordForm = this.fb.group({ 
      'password': ['', [Validators.required, Validators.minLength(8)]],
      'confirmPassword': ['', Validators.required]
    },{
          validator: PasswordValidation.MatchPassword // your validation method
    });

    this.signupAgencyMemberForm = this.fb.group({
      'name': ['', [Validators.required, Validators.minLength(3)]],
      'business_name': ['', Validators.required],
      'email': [''],
      'phone': [''],
      // 'confirm_email': [''],
      'device_type': ['web_browser'],
      'user_sub_type': ['USER'],
      'organization_member_id': [''],
      'pricing_plan_id': [this.pricing_plan_id],
      'plan_name': ['Select Plan'],
      'password': ['', [Validators.required, Validators.minLength(8)]]
    },{
      // validator: EmailValidation.MatchEmail // your validation method
    });

    this.signupTeamMemberForm = this.fb.group({
      'name': ['', [Validators.required, Validators.minLength(3)]],
      'email': [''],
      'phone': [''],
      'device_type': ['web_browser'],
      'user_sub_type': ['USER'],
      'organization_member_id': [''],
      'password': ['', [Validators.required, Validators.minLength(8)]]
    });

    this.signupForm = this.fb.group({
      'name': ['', [Validators.required, Validators.minLength(3)]],
      'pricing_plan_id': ['', Validators.required],
      'business_name': ['', Validators.required],
      'email': [''],
      'phone': [''],
      'confirm_email': [''],
      'device_type': ['web_browser'],
      'user_sub_type': ['USER'],
      'amount': [0],
      'plan_name': ['Select Plan'],
      'password': ['', [Validators.required, Validators.minLength(8)]]
    },{
      validator: EmailValidation.MatchEmail // your validation method
    });

    this.paymentForm = this.fb.group({
      'stripe_token_id': [],
      'payment_card': ['', [Validators.required]],
      'is_card_ready': [],
      'billing_address': ['', [Validators.required]],
      'city': ['', Validators.required],
      'state': ['', Validators.required],
      'zip_code': ['', Validators.required],
      'country': ['231', Validators.required],
      });
  }

  showDiv(){
    if (this.router.url === '/users/login') {
      this.is_show = 'LOGIN';
    }else if(this.router.url === '/users/signup-individual/' + this.code) {
      this.checkPricingPlanStatus(this.code, 'DIRECT');
      this.is_show = 'SIGNUP_INDIVIDUAL';
    }else if(this.router.url === '/users/signup-agency/' + this.code) {
      this.checkPricingPlanStatus(this.code, 'DIRECT');
      this.is_show = 'SIGNUP_AGENCY';
    }else if(this.router.url === '/users/signup-agency-member/'+this.code+'?plan_id='+this.pricing_plan_id) {
      this.checkPricingPlanStatus(this.pricing_plan_id, 'INVITED');
      this.is_show = 'SIGNUP_AGENCY_MEMBER';
      this.getEmailById(this.code, 'SIGNUP_AGENCY_MEMBER');
    }else if(this.router.url === '/users/signup-team-member/'+this.code) {
      this.is_show = 'SIGNUP_TEAM_MEMBER';
      this.getEmailById(this.code, 'SIGNUP_TEAM_MEMBER');
    }else if(this.router.url === '/users/payment') {
      this.is_show = 'PAYMENT';
      this.getCountries()
      setTimeout(() => {
        this.inItStriepForm();
      }, 30);
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

  getEmailById(organization_member_id, type){
    this.userService.getEmailById({organization_member_id: organization_member_id}).subscribe((response: any) => {
      if (response.data.email) {
        if (type == 'SIGNUP_TEAM_MEMBER') {
          let email = this.signupTeamMemberForm.get('email');
          email.setValue(response.data.email);
        } else {
          let email = this.signupAgencyMemberForm.get('email');
          email.setValue(response.data.email);
        }
      }else{
        this.router.navigate(['/users/login']);
      }
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.router.navigate(['/users/login']);
    });
  }

  getCountries(){
    this.userService.getCountries().subscribe( 
      (data:any) => {
        this.countries = data;
      },
      err => {
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
      }
    );
  }

  checkPricingPlanStatus(code, type){
    let credentials = {
      "pricing_plan_id" : code
    }
    this.userService.checkPricingPlanStatus(credentials).subscribe( 
      (data:any) => {
        // this.plan_name = data.data.plan_name;
        let plan_name = this.signupForm.get('plan_name');
        plan_name.setValue(data.data.plan_name);
        this.userService.userData.plan_name = data.data.plan_name;
        if (type === 'INVITED') {
          let pricing_plan_id = this.signupAgencyMemberForm.get('pricing_plan_id');
          pricing_plan_id.setValue(this.pricing_plan_id);
        } else {
          let pricing_plan_id = this.signupForm.get('pricing_plan_id');
          pricing_plan_id.setValue(this.code);
          let amount = this.signupForm.get('amount');
          amount.setValue(data.data.amount);
        } 
      },
      err => {
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
      }
    );
  }

  ngOnInit() {}

  ngAfterViewInit() {}

  isMaps(path){
      var titlee = this.location.prepareExternalUrl(this.location.path());
      titlee = titlee.slice( 1 );
      if(path == titlee){
          return false;
      }
      else {
          return true;
      }
  }

// LOGIN SECTION

submitForm() {
  this.isSubmitting = true;
  const credentials = this.authForm.value;
  this.userService
    .attemptAuthLogin(credentials)
    .subscribe(
      (data: any) => {
        this.isSubmitting = false;
        // this.userService.alerts.push({
        //   type: 'success',
        //   msg: data.message,
        //   timeout: 4000
        // });
        if (data.data.user_type === 'USER') {
          this.router.navigate(['/user/user-dashboard']);
        } else {
          this.router.navigate(['/console/dashboard']);
        }
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


// FORGOT PASSWORD
submitForgotPasswordForm() {
  this.isSubmitting = true; 
  const credentials = this.forgotPasswordForm.value;
  this.userService
  .forgotPassword(credentials)
  .subscribe( 
    data => {
      this.isSubmitting = false;
      this.userService.thankyoudata =  data.message;
      this.userService.alerts.push({
        type: 'success',
        msg: data.message,
        timeout: 4000
      }); 
      this.router.navigate(['/users/password-success']); 
    //   setTimeout(() => { 
    //     this.router.navigate(['/users/password-success']);        
    //  }, 1000);   
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

submitTeamMemberSignupForm(){
  this.isSubmitting = true;
  let credentials = this.signupForm.value;
  let organization_member_id = this.signupTeamMemberForm.get('organization_member_id');
  organization_member_id.setValue(this.code);
  credentials = this.signupTeamMemberForm.value;
  // console.log(credentials);
  // return;
  this.userService
  .submitTeamMemberSignupForm(credentials)
  .subscribe(
    data => {
      this.isSubmitting = false;
      // this.userService.thankyoudata = data;
      this.userService.thankyoudata = 'You have successfully signup with Marketing Tiki.';
      this.userService.alerts.push({
        type: 'success',
        msg: 'You have successfully signup with Marketing Tiki.',
        timeout: 4000
      }); 
      this.router.navigate(['/users/signup-success']);  
      // setTimeout(() => {
      //   this.router.navigate(['/users/signup-success']);   
      // }, 1000);  
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

submitSignupForm(type) {
  this.isSubmitting = true;
  let credentials = this.signupForm.value;
  if (type === 'AGENCY') {
    let user_sub_type = this.signupForm.get('user_sub_type');
    user_sub_type.setValue('AGENCY');
    credentials = this.signupForm.value;
  } 
  
  if(type === "AGENCY_USER") {
    let user_sub_type = this.signupAgencyMemberForm.get('user_sub_type');
    user_sub_type.setValue('AGENCY_USER');
    let organization_member_id = this.signupAgencyMemberForm.get('organization_member_id');
    organization_member_id.setValue(this.code);
    credentials = this.signupAgencyMemberForm.value;
  }
  // console.log(credentials);
  // return;
  this.userService
  .attemptAuthRegister(credentials)
  .subscribe(
    data => {
      this.isSubmitting = false;
      // this.userService.thankyoudata = data;
      this.userService.thankyoudata = 'You have successfully subscribed with Marketing Tiki.';
      this.userService.alerts.push({
        type: 'success',
        msg: 'You have successfully subscribed with Marketing Tiki.',
        timeout: 4000
      }); 
      this.router.navigate(['/users/signup-success']);  
      // setTimeout(() => {
      //   this.router.navigate(['/users/signup-success']);   
      // }, 1000);  
    },
    err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.isSubmitting = false;
    }
  );}

  submitResetPasswordForm() {
    this.isSubmitting = true; 
    const credentials = this.resetPasswordForm.value;
    credentials.password_reset_code = this.code;
    this.userService
    .resetPassword(credentials)
    .subscribe( 
      data => {  
        this.isSubmitting = false;
        // this.userService.thankyoudata = 'You have successfully set your new password.';
        this.userService.thankyoudata = data.message;
         this.userService.alerts.push({
          type: 'success',
          msg: data.message,
          timeout: 4000
        }); 
        this.router.navigate(['/users/password-success']);
        // setTimeout(() => {
        //     this.router.navigate(['/users/password-success']);     
        // }, 4000);         
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
  submitFinalForm() {
    this.isSubmitting = true;
    this.stripeService
      .createToken(this.card, { name })
      .subscribe(result => {
        if (result.token){
          let stripe_token_id_control = this.paymentForm.get('stripe_token_id');
          stripe_token_id_control.setValue(result.token.id);
          let user:any={};
          user.name = this.userService.userData.name;
          user.email = this.userService.userData.email;
          user.business_name = this.userService.userData.business_name;
          user.password = this.userService.userData.password;
          user.user_sub_type = this.userService.userData.user_sub_type;
          user.phone = this.userService.userData.phone;
          user.pricing_plan_id = this.userService.userData.pricing_plan_id;

          user.stripe_token_id = this.paymentForm.value.stripe_token_id;
          user.payment_card = this.paymentForm.value.payment_card;
          user.is_card_ready = this.paymentForm.value.is_card_ready;
          user.billing_address = this.paymentForm.value.billing_address;
          user.city = this.paymentForm.value.city;
          user.state = this.paymentForm.value.state;
          user.zip_code = this.paymentForm.value.zip_code;
          user.country = this.paymentForm.value.country;

          // plan GUID from url
          // user.pricing_plan_id = '690dc268-23f4-450b-b6c0-ceabb5b9ecf5';
          //Create customer, Charge amount
          // console.log(user);
          // return;
          this.userService
            .paymentAndRegister(user)
            .subscribe(
              data => {
                this.isSubmitting = false;
                this.userService.thankyoudata =  data.message;
              this.userService.alerts.push({
                type: 'success',
                msg: data.message,
                timeout: 4000
              }); 
              this.router.navigate(['/users/password-success']);                      
              },
              err => {
                this.isSubmitting = false;
                this.userService.alerts.push({
                  type: 'danger',         
                  msg: "Error in submission:"+err.message,
                  timeout: 4000
                });
              }
          );
        
        } else if (result.error) {
          // Error creating the token
          this.isSubmitting = false;    
          this.userService.alerts.push({
            type: 'danger',         
            msg: ""+result.error.message,
            timeout: 4000
          });   
        }
    });
  }
  
  goToNext(type) {   
    if (type === 'AGENCY') {
      let user_sub_type = this.signupForm.get('user_sub_type');
      user_sub_type.setValue('AGENCY');
    } 
    
    if(type === "AGENCY_USER") {
      let user_sub_type = this.signupForm.get('user_sub_type');
      user_sub_type.setValue('AGENCY_USER');
    }
    this.isSubmitting = true; 
    const credentials = this.signupForm.value;
    //check for duplicate email and phone
    this.userService.check_unique_email_phone(credentials.email, credentials.phone)
    .subscribe(
        data => {
          this.isSubmitting = false; 
          if (this.save(credentials)) {
            this.isSubmitting = true;
            this.router.navigate(['/users/payment']);
          }
        },
        err => {
            this.isSubmitting = false; 
            this.userService.alerts.push({
                type: 'danger',         
                msg: err.message,
                timeout: 4000
              });      
            //if email and phone are duplicate then focus on fields
            // if ( err.errors.email ) {
            //     this.renderer.selectRootElement('#email').focus();
            // }
            // if ( err.errors.phone ) {
            //     this.renderer.selectRootElement('#phone').focus();
            // }              
    });
}

inItStriepForm(){
  this.stripeService.elements(this.elementsOptions)
  .subscribe(elements => {
    this.elements = elements;
    // Only mount the element the first time
    if (!this.card) {
      this.card = this.elements.create('card', {
        style: {
          base: {
            iconColor: '#666EE8',
            color: '#31325F',
            lineHeight: '40px',
            fontWeight: 300,
            fontFamily: 'inherit',
            fontSize: '16px',
            '::placeholder': {
              color: '#8E8E8E',

            }
          }
        }
      });
      this.card.mount('#card-element');

      this.card.on('change', (e)=>{
        if(e.complete){
          let payment_card_control = this.paymentForm.get('payment_card');
          payment_card_control.setValidators([]);
          payment_card_control.updateValueAndValidity();
          let is_card_ready_control = this.paymentForm.get('is_card_ready');
          is_card_ready_control.setValue('1');
        }else {
          let is_card_ready_control = this.paymentForm.get('is_card_ready');
          is_card_ready_control.setValue('');
          let payment_card_control = this.paymentForm.get('payment_card');
          payment_card_control.setValidators([Validators.required]);
          payment_card_control.updateValueAndValidity();
        }               
      }); 
    }
  });
}

  save(form: any): boolean {       
    this.userService.setPersonal(form);
    return true;
  }

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }
}
