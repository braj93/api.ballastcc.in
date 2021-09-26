import { Component, OnInit } from '@angular/core';
import { AdminService } from '../../core';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  public details: any;
  public counts: any;
  public isSubmitting: boolean = false;
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
    filters: {
      status: '',
    }
  };
  public rows: any = [];
  public pages: any;
  public current_page: any = 1;
  public last_index: any;
  public currentUser: any;
  constructor(
    public route: ActivatedRoute,
    public adminService: AdminService,
  ) { }

  ngOnInit(): void {
    this.adminService.page_title = 'Dashboard';
    this.route.data.subscribe((data: any) => {
      
      const result = data['dashboard_details'];
      this.details = result.data;
      this.getpaymentsList();
      // console.log(this.details);
    });
  }
  getpaymentsList() {
    this.adminService.getPaymentsList(this.parameters).subscribe((response: any) => {
      
      this.isSubmitting = true;
      this.rows = response.data;
      this.counts = response.counts;
      this.isSubmitting = false;
      console.log(this.rows);
      // FOR PAGINATION
      // let number: any = this.counts / this.parameters.pagination.limit;
      const number: any = Math.ceil(this.counts / this.parameters.pagination.limit);

      this.pages = Array.from(Array(number).keys());
      this.last_index = this.pages.length;
    }, err => {
      this.adminService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.isSubmitting = false;
    });
  }
  ngOnDestroy(): void{
    this.adminService.alerts = [];
  }

}
