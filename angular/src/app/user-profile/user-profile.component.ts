import { Component, ViewChild, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { UserService, AdminService } from '../core';
import { DatatableComponent } from '@swimlane/ngx-datatable';
import { DaterangepickerConfig, Daterangepicker, DaterangepickerComponent } from 'ng2-daterangepicker';
import * as moment from 'moment';
declare var $;

@Component({
  selector: 'app-user-profile',
  templateUrl: './user-profile.component.html',
  styleUrls: ['./user-profile.component.css']
})
export class UserProfileComponent implements OnInit, OnDestroy {
  // @ViewChild(DaterangePickerComponent) picker: DaterangePickerComponent;
  @ViewChild(DaterangepickerComponent)
  private picker: DaterangepickerComponent;
  public isSubmitting: boolean = false;
  public alerts: any = [];
  public rows: any = [];
  public loading: boolean = false;
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
    filters: {
      status: '',
      package: '',
    },
    last_login_at: {
      start: '',
      end: ''
    }
  };
  public plans: any;
  public daterangedefault: any = {
    'start': moment().startOf('month'),
    'end': moment().endOf('month')
  };
  public daterange: any = {
    'start': moment().startOf('month'),
    'end': moment().endOf('month')
  };
  public currentDate: any;
  constructor(
    public userService: UserService,
    public adminService: AdminService,
    private route: ActivatedRoute,
    public daterangepickerOptions: DaterangepickerConfig
  ) {

    this.daterangepickerOptions.settings = {
      locale: {
        format: 'MMM DD, YYYY',
        separator: ' to '
      },
      alwaysShowCalendars: false,
      ranges: {
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'Last 60 Days': [moment().subtract(59, 'days'), moment()],
        'Last 90 Days': [moment().subtract(89, 'days'), moment()],
        'Last 180 Days': [moment().subtract(179, 'days'), moment()],
      },
      skipCSS: true,
      // startDate: this.daterangedefault.start,
      // endDate: this.daterangedefault.end
      // maxDate: moment()
    };
    this.currentDate = moment();
  }

  ngOnInit(): void {
    this.userService.page_title = 'User Management';
    this.route.data.subscribe((data: any) => {
      const result = data['users'];
      // this.loading = true;
      this.rows = result.data;
      // this.loading = false;
      this.counts = result.counts;
    });
    this.getPlans();
  }

  setPage(event) {
    this.page_no = event.offset;
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.userService.usersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  onSort(event) {
    // console.log("event", event);
    this.parameters.sort_by.column_name = event.sorts[0].prop === 'name' ? 'first_name' : event.sorts[0].prop;
    this.parameters.sort_by.order_by = event.sorts[0].dir;
    // console.log("this.parameters", this.parameters);
    this.userService.usersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  agencyDemo(name) {
    this.userService.my_title = name;
  }

  // getUsersList(){
  //   this.userService.usersList(this.parameters).subscribe((response: any) => {
  //     this.isSubmitting = true;
  //     this.rows = response.data;
  //     this.counts = response.counts;
  //     this.isSubmitting = false;
  //   }, err => {
  //     this.userService.alerts.push({
  //       type: 'danger',
  //       msg: err.message,
  //       timeout: 4000
  //     });
  //     this.isSubmitting = false;
  //   });
  // }

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }

  search(event) {
    if (this.parameters.keyword === '') {
      this.page_no = 0;
    }
    this.parameters.pagination.offset = 0;
    this.userService.usersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
    });
  }

  filterStatus() {
    this.parameters.pagination.offset = 0;
    this.userService.usersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
    }, err => {
      console.log(err.message);
    });
  }

  getPlans() {
    this.adminService.plansList().subscribe((response: any) => {
      this.plans = response.data
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  filterPackage() {
    this.parameters.pagination.offset = 0;
    this.userService.usersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
    }, err => {
      console.log(err.message);
    });
  }

  selectedDate(value: any) {
    this.parameters.last_login_at.start = moment(value.start._d).format('YYYY-MM-DD HH:mm:ss');
    this.parameters.last_login_at.end = moment(value.end._d).format('YYYY-MM-DD HH:mm:ss');
    this.parameters.pagination.offset = 0;
    this.userService.usersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
    }, err => {
      console.log(err.message);
    });
  }

  reset() {
    this.parameters.keyword = '';
    this.parameters.filters.status = '';
    this.parameters.filters.package = '';
    this.parameters.last_login_at.start = '';
    this.parameters.last_login_at.end = '';
    this.parameters.pagination.offset = 0;
    this.picker.datePicker.setStartDate(this.currentDate);
    this.picker.datePicker.setEndDate(this.currentDate);
    this.userService.usersList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
    }, err => {
      console.log(err.message);
    });
  }

  chageStatus(id, status, index) {
    const params = {
      'user_id': id,
      'status': status
    }
    // let index = this.userService.getIndexOfList(this.rows, 'user_guid', id);
    this.userService.changeUserStatus(params).subscribe((response: any) => {
      this.rows[index].status = status;
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

  sendResetPasswordLink(id) {
    this.adminService.resetPasswordLink({ user_id: id }).subscribe((response: any) => {
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });

  }
}
