import { Component, OnInit } from '@angular/core';
import { AdminService } from '../../core';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-fee-management',
  templateUrl: './fee-management.component.html',
  styleUrls: ['./fee-management.component.scss']
})
export class FeeManagementComponent implements OnInit {
  // public details: any;
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
    this.adminService.page_title = 'Fee Management';
    this.getpaymentsList();
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
  paginationAction(action:any) {
    this.current_page = action === 'next' ? this.current_page + 1 : this.current_page - 1;
    this.parameters.pagination.offset = (this.current_page - 1) * this.parameters.pagination.limit;
    this.adminService.getStudentsList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }
  setPage(page_no:any) {
    this.current_page = page_no;
    this.parameters.pagination.offset = (page_no - 1) * this.parameters.pagination.limit;
    this.adminService.getStudentsList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }
  onSort(event:any) {

  }
  search(event:any) {
    if (this.parameters.keyword === '') {
      this.page_no = 0;
    }
    this.parameters.pagination.offset = 0;
    this.adminService.getStudentsList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
      const number: any = Math.ceil(this.counts / this.parameters.pagination.limit);
      this.pages = Array.from(Array(number).keys());
    }, err => {
      console.log(err.message);
    });
  }
  ngOnDestroy(): void{
    this.adminService.alerts = [];
  }

}
