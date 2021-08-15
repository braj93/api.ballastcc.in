import { Component, TemplateRef, OnInit, OnDestroy } from '@angular/core';
import { UserService, AdminService } from '../../core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';

@Component({
  selector: 'app-pricing-plans',
  templateUrl: './pricing-plans.component.html',
  styleUrls: ['./pricing-plans.component.css']
})
export class PricingPlansComponent implements OnInit, OnDestroy {
  public modalRef: BsModalRef;
  public message = '';
  public pricing_plan_id = '';
  public isSubmitting: Boolean = false;
  public alerts: any = [];
  public rows: any = [];
  public loading: Boolean = false;
  public counts: any;
  public page_no: any = 0;
  public parameters: any = {
    keyword: '',
    pagination: {
      offset: 0,
      limit: 10
    },
    sort_by: {
      column_name: '',
      order_by: '',
    },
    filter: ''
  };
  constructor(
    public modalService: BsModalService,
    public userService: UserService,
    public adminService: AdminService
  ) {
  }

  ngOnInit(): void {
    this.userService.page_title = 'Pricing Plans';
    this.getPricingPlansList();
  }

  setPage(event) {
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.adminService.getPricingPlansList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  onSort(event) {
    this.page_no = event.offset;
    this.parameters.sort_by.column_name = event.sorts[0].prop === 'name' ? 'first_name' : event.sorts[0].prop;
    this.parameters.sort_by.order_by = event.sorts[0].dir;
    this.adminService.getPricingPlansList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }


  getPricingPlansList() {
    this.adminService.getPricingPlansList(this.parameters).subscribe((response: any) => {
      this.isSubmitting = true;
      this.rows = response.data;
      this.counts = response.counts;
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

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }

  search(event) {
    if (this.parameters.keyword === '') {
      this.page_no = 0;
    }
    this.parameters.pagination.offset = 0;
    this.adminService.getPricingPlansList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
    });
  }

  openModal(template: TemplateRef<any>, pricing_plan_id) {
    this.message = 'Are you sure you want to delete!';
    this.pricing_plan_id = pricing_plan_id
    this.modalRef = this.modalService.show(template, { class: 'modal-md' });
  }

  confirm(): void {
    this.deletePricinPlan(this.pricing_plan_id);
    this.modalRef.hide();
  }

  decline(): void {
    this.pricing_plan_id = '';
    this.message = 'Declined!';
    this.modalRef.hide();
  }

  deletePricinPlan(pricing_plan_id) {
    this.adminService.deletePricingPlan({ pricing_plan_id: pricing_plan_id }).subscribe((response: any) => {
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.getPricingPlansList();
    },
      err => {
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
        setTimeout(() => {
          this.userService.alerts = [];
        }, 2000);
        this.isSubmitting = false;
      });
  }

  changePricingPlanStatus(id, status, index) {
    const params = {
      'pricing_plan_id': id,
      'status': status
    }
    console.log(this.rows[index].status);
    this.adminService.changePricingPlanStatus(params).subscribe((response: any) => {
      this.rows[index].status = status;
      console.log(this.rows[index].status);
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
      });
  }
}
