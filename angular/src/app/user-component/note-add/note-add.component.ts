import { Component, OnInit } from '@angular/core';
import { UserService, CrmService } from '../../core';
import { ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import * as moment from 'moment';
import { Location } from '@angular/common';

@Component({
  selector: 'app-note-add',
  templateUrl: './note-add.component.html',
  styleUrls: ['./note-add.component.css']
})
export class NoteAddComponent implements OnInit {
  public isSubmitting: any = false;
  public detail: any;
  public alerts: any = [];
  public addNoteForm: FormGroup;
  public param_id: any;
  public rows: any;
  public counts: any;
  public logs: any = [];
  public logs_counts: any = 0;
  public pages: any;
  public current_page: any = 1;
  public last_index: any;
  public parameters: any = {
    keyword: '',
    pagination: {
      offset: 0,
      limit: 6
    },
    sort_by: {
      column_name: '',
      order_by: '',
    }
  };
  constructor(
    public location: Location,
    public userService: UserService,
    public crmService: CrmService,
    public route: ActivatedRoute,
    private fb: FormBuilder,
  ) {
    this.addNoteForm = this.fb.group({
      'note': ['', [Validators.required]]
    });
  }

  ngOnInit(): void {
    this.route.data.subscribe((data: any) => {
      const result = data['contact_detail'];
      this.detail = result.data;
    });
    this.userService.page_title = 'Contact Info';
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.getNotesList();
    this.getLogsList();
  }

  getDate(date) {
    const yourDate: Date = new Date(date + ' UTC');
    return yourDate;
  }

  formatPhoneNumber(number) {
    const number_val = number.replace(/\D[^\.]/g, '');
    const str = '(' + number_val.slice(0, 3) + ') ' + number_val.slice(3, 6) + '-' + number_val.slice(6);
    return str;
  }

  mail(email, crm_contact_id) {
    this.crmService.addLogs({
      crm_contact_id : this.detail.crm_contact_guid,
      type: 'EMAIL'
    }).subscribe((response: any) => {
      this.getLogsList();
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  getLogsList() {
    this.parameters.crm_contact_id = this.param_id;
    this.crmService.getLogsList(this.parameters).subscribe((response: any) => {
      this.logs = response.data;
      this.logs_counts = response.counts;
      const number = Math.ceil(this.logs_counts / this.parameters.pagination.limit);
      this.pages = Array.from(Array(number).keys());
      this.current_page = 1;
      this.last_index = this.pages.length;
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  setPage(page_no) {
    this.current_page = page_no;
    this.parameters.pagination.offset = (page_no - 1) * this.parameters.pagination.limit;
    this.crmService.getLogsList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.logs_counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  paginationAction(action) {
    if (this.logs_counts !== this.logs.length) {

    }
    this.current_page = action === 'next' ? this.current_page + 1 : this.current_page - 1;
    this.parameters.pagination.offset = (this.current_page - 1) * this.parameters.pagination.limit;
    this.crmService.getLogsList(this.parameters).subscribe((response: any) => {
      this.logs = response.data;
      this.logs_counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  getNotesList() {
    // console.log(this.parameters, " -- --- -- params");
    this.crmService.getNotesList({ crm_contact_id: this.param_id }).subscribe((response: any) => {
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

  submitForm() {
    // console.log("this.addNoteForm.value", this.addNoteForm.value);
    this.addNoteForm.value.crm_contact_id = this.param_id;
    this.isSubmitting = true;
    this.crmService.addNotes(this.addNoteForm.value).subscribe((response: any) => {
      this.userService.alerts.push({
        type: 'success',
        msg: 'Added Successfully!',
        timeout: 4000
      });
      this.addNoteForm.reset();
      this.getNotesList();
      setTimeout(() => {
        this.isSubmitting = false;
        this.alerts = [];
      }, 1000);
    }, err => {
      this.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      setTimeout(() => {
        this.isSubmitting = false;
        this.userService.alerts = [];
      }, 2000);
      setTimeout(() => {
        this.isSubmitting = false;
      }, 2000);
    });
  }

  momentAgo(date, format = 'hh:mm A') {
    const localTime = moment.utc(date).toDate();
    return moment(localTime).format(format);
  }

  back() {
    this.location.back();
  }
}
