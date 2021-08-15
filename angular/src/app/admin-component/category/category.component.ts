import { Component, ViewChild, OnInit, OnDestroy } from '@angular/core';
import { UserService, AdminService } from '../../core';
declare var $;

@Component({
  selector: 'app-category',
  templateUrl: './category.component.html',
  styleUrls: ['./category.component.css']
})
export class CategoryComponent implements OnInit, OnDestroy {
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
    public userService: UserService,
    public adminService: AdminService
  ) {
  }

  ngOnInit(): void {
    this.userService.page_title = 'Category';
    this.getCategoryList();
  }

  setPage(event) {
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.adminService.getCategoryList(this.parameters).subscribe((response: any) => {
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
    this.adminService.getCategoryList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }


  getCategoryList() {
    this.adminService.getCategoryList(this.parameters).subscribe((response: any) => {
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
    this.adminService.getCategoryList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
    });
  }
}
