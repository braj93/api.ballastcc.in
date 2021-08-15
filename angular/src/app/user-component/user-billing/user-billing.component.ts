import { Component, OnInit, OnDestroy } from '@angular/core';
import { UserService, ApiService } from '../../core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { ActivatedRoute } from '@angular/router';
import * as moment from 'moment';
@Component({
  selector: 'app-user-billing',
  templateUrl: './user-billing.component.html',
  styleUrls: ['./user-billing.component.css']
})
export class UserBillingComponent implements OnInit, OnDestroy {
  public modalRef: BsModalRef;
  public isSubmitting = false;
  public alerts: any = [];
  public rows: any = [];
  public loading = false;
  public counts: any;
  public page_no: any = 0;
  public param_id: any;
  public details: any;
  public currentUser: any = '';
  public userDetails: any = '';
  public config = {
    backdrop: true,
    ignoreBackdropClick: false,
    class: 'modal-md'
  };
  constructor(
    public apiService: ApiService,
    public userService: UserService,
    public modalService: BsModalService,
    public route: ActivatedRoute,
  ) {

  }

  ngOnInit(): void {
    this.userService.currentUser.subscribe(
      (userData) => {
        this.currentUser = userData;
      }
    );
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.route.data.subscribe((data: any) => {
      this.details = data['subscription_details'].data;
      this.userDetails = data['userDetails'].data;
      if (this.currentUser.user_type === 'OWNER' || this.currentUser.user_type === 'ADMIN') {
        this.userService.page_title =  'Billing - ' + this.userDetails.name;
      } else {
        this.userService.page_title = 'Billing';
      }
      this.getInvoces();
    });
  }

  getInvoces() {
    this.userService.getInvoces({ user_id: this.param_id }).subscribe((response: any) => {
      this.rows = response.data.data;
      this.counts = this.rows.length;
    }, err => {
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

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }
}
