import { Component, ViewChild, OnInit } from '@angular/core';
import { UserService, AdminService } from '../../core';

declare var $;

@Component({
  selector: 'app-user-broadcast-profile',
  templateUrl: './user-broadcast.component.html',
  styleUrls: ['./user-broadcast.component.css']
})
export class UserBroadcastComponent implements OnInit {
  public isSubmitting = false;
  public alerts: any = [];
  public rows: any = [];
  public loading = false;
  public counts: any;
  public page_no: any = 0;
  public parameters: any = {
    pagination: {
      offset: 0,
      limit: 10
    },
    sort_by: {
      column_name: '',
      order_by: '',
    }
  };
  constructor(
    public userService: UserService,
    public adminService: AdminService
  ) {
  }

  ngOnInit(): void {
    this.userService.page_title = 'News & Updates';
    this.getList();
  }

  getList() {
    this.adminService.getBroadcastListByUser(this.parameters).subscribe((response: any) => {
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

  setPage(event) {
    this.page_no = event.offset;
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.adminService.getBroadcastList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  onSort(event) {
    this.parameters.sort_by.column_name = event.sorts[0].prop === 'broadcastTitle' ? 'title' : event.sorts[0].prop;
    this.parameters.sort_by.order_by = event.sorts[0].dir;
    this.adminService.getBroadcastList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }
}
