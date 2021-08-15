import { Component, OnInit, OnDestroy, TemplateRef } from '@angular/core';
import { UserService, ApiService, AdminService } from '../../core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { ActivatedRoute, Router } from '@angular/router';
import * as moment from 'moment';
import { Location } from '@angular/common';
@Component({
  selector: 'app-user-plan-update',
  templateUrl: './user-plan-update.component.html',
  styleUrls: ['./user-plan-update.component.css']
})
export class UserPlanUpdateComponent implements OnInit, OnDestroy {
  message: string;
  public currentUser: any;
  public loggedInUser: any;
  public modalRef: BsModalRef;
  public isSubmitting: any = false;
  public plans: any = [];
  public alerts: any = [];
  public rows: any = [];
  public loading: any = false;
  public counts: any;
  public page_no: any = 0;
  public param_id: any;
  public details: any;
  public plan_id: any = '';
  public plan_type: any = '';
  public config = {
    backdrop: true,
    ignoreBackdropClick: false,
    class: 'modal-md'
  };
  constructor(
    public location: Location,
    public router: Router,
    public apiService: ApiService,
    public userService: UserService,
    public adminService: AdminService,
    public modalService: BsModalService,
    public route: ActivatedRoute,
  ) {

  }

  ngOnInit(): void {
    this.userService.page_title = 'Update Plan';
    this.userService.currentUser.subscribe(
      (userData) => {
        this.loggedInUser = userData;
        console.log(this.loggedInUser);
      });
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.route.data.subscribe((data: any) => {
      this.details = data['subscription_details'].data;
      this.currentUser = data['userDetails'].data;
      if (this.currentUser.user_type === 'USER' && this.currentUser.user_role === 'USER_AGENCY_OWNER') {
        this.plan_type = 'AGENCY';
      } else {
        this.plan_type = 'NON_AGENCY';
      }
      this.getPlans();
    });
  }

  getPlans() {
    this.adminService.getPlansByType({ type: this.plan_type }).subscribe((response: any) => {
      this.plans = response.data;
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  updatePlan() {
    this.userService.updatePlan({ user_id: this.param_id, plan_id: this.plan_id }).subscribe((response: any) => {
      this.userService.alerts.push({
        type: 'success',
        msg: 'Updated Succesfully',
        timeout: 4000
      });
      if (this.loggedInUser.user_type === 'USER') {
      this.userService.userProfileRefresh();
      }
      this.router.navigate(['/user/billing/' + this.param_id]);
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      console.log(err.message);
    });
  }


  getCurrency(num) {
    let dollars: any = num / 100;
    dollars = dollars.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
    return dollars;
  }

  getTime(time) {
    const result: any = moment.unix(time).format('MMM DD, YYYY');
    return result;
  }

  openModal(template: TemplateRef<any>, plan) {
    this.plan_id = plan.stripe_pricing_plan_id;
    const plan_price = plan.base_price;
    const base_price: any  = parseInt(plan.base_price);
    const intamount: any = this.details.subscription.plan.amount / 100;
    const amount: any  = parseInt(intamount);
    const date: any = this.getTime(this.details.subscription.current_period_end)
    if (base_price > amount) {
      this.message = 'You will be charged $' + plan_price + ' today to update your plan and have immediate access to the features of the ' + plan.name + ' plan you selected'
    } else {
      this.message = 'You will be charged $' + plan_price + ' on the next billing cycle and retain your existing plan until ' + date + ' date when your plan renews';
    }
    this.modalRef = this.modalService.show(template, { class: 'modal-md' });
  }

  confirm(): void {
    // this.message = 'Confirmed!';
    this.updatePlan();
    this.modalRef.hide();
  }

  decline(): void {
    this.plan_id = ''
    this.message = 'Declined!';
    this.modalRef.hide();
  }

  goToBack() {
    this.location.back();
  }

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }
}
