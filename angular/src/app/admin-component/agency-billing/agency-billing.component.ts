import { Component, OnDestroy, OnInit } from '@angular/core';
import { UserService, CrmService } from '../../core';
import * as moment from 'moment';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { ngxCsv } from 'ngx-csv/ngx-csv';
import { environment } from '../../../environments/environment';
declare var $;

@Component({
  selector: 'app-agency-billing',
  templateUrl: './agency-billing.component.html',
  styleUrls: ['./agency-billing.component.css']
})
export class AgencyBillingComponent implements OnInit, OnDestroy {
  public current_page: any = 1;
  public pages: any;
  public last_index: any;

  public modalRef: BsModalRef;
  public config_invite_users = {
    backdrop: true,
    ignoreBackdropClick: true,
    class: 'modal-lg'
  };
  public isSubmitting = false;
  public alerts: any = [];
  public rows: any = [];
  public loading = false;
  public counts: any;
  public currentUser: any;
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
    public modalService: BsModalService,
    public crmService: CrmService
  ) { }

  ngOnInit(): void {
    this.userService.currentUser.subscribe(
      (userData) => {
        this.currentUser = userData;
      });
    this.userService.page_title = 'Agency Billing';
    this.getAgencyBillingContacts();
  }

  formatPhoneNumber(number) {
    const number_val = number.replace(/\D[^\.]/g, '');
    const str = '(' + number_val.slice(0, 3) + ') ' + number_val.slice(3, 6) + '-' + number_val.slice(6);
    return str;
  }

  // setPage(event) {
  //   this.parameters.pagination.limit = event.pageSize;
  //   this.parameters.pagination.offset = event.offset * event.pageSize;
  //   this.crmService.getAgencyBillingContacts(this.parameters).subscribe((response: any) => {
  //     this.rows = response.data;
  //     this.counts = response.counts;
  //   }, err => {
  //     console.log(err.message);
  //   });
  // }

  setPage(page_no) {
    this.current_page = page_no;
    this.parameters.pagination.offset = (page_no - 1) * this.parameters.pagination.limit;
    this.crmService.getAgencyBillingContacts(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  paginationAction(action) {
    this.current_page = action === 'next' ? this.current_page + 1 : this.current_page - 1;
    this.parameters.pagination.offset = (this.current_page - 1) * this.parameters.pagination.limit;
    this.crmService.getAgencyBillingContacts(this.parameters).subscribe((response: any) => {
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
    // console.log("this.parameters", this.parameters);
    this.crmService.getAgencyBillingContacts(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }


  getAgencyBillingContacts() {
    this.crmService.getAgencyBillingContacts(this.parameters).subscribe((response: any) => {
      this.isSubmitting = true;
      this.rows = response.data;
      this.counts = response.counts;
      this.isSubmitting = false;
      // FOR PAGINATION
      // let number: any = this.counts / this.parameters.pagination.limit;
      const number: any = Math.ceil(this.counts / this.parameters.pagination.limit);
      this.pages = Array.from(Array(number).keys());
      this.last_index = this.pages.length;
      // console.log(this.last_index);
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
    this.crmService.getAgencyBillingContacts(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
    });
  }

  getObject(key, array_value) {
    // console.log(key);
    // console.log(array_value);
    // return
    array_value[key] = array_value[key] || '--';
    // array_value.more_info = array_value.more_info || [];
    // array_value.more_info.forEach((more_info_obj, more_info_idx) => {
    //   if (more_info_obj.field_name === key) {
    //     more_info_obj.field_value = more_info_obj.field_value || '--';
    //     array_value[key] = more_info_obj.field_value;
    //   }
    // });
    console.log(array_value[key]);
    return array_value[key];
  }


  exportFile(user_id) {
    const path = environment.site_addr + '/site/export_billing?id=' + user_id;
    window.open(path, '_blank');
  }

  exportToCsvgetAllLeads(result) {
    const agency_users = result.agency_users || [];
    const headers: any = ['Agency Name', 'Business Name', 'Plan', 'Signup Date', 'Days In This Billing Cycle', 'Amount To Be Billed'];
    const final_rows: any = [];
    const result_array: any = [];
    const temps: any = {}
    if ((result.hasOwnProperty('name'))) {
      temps['Agency Name'] = result['name'];
    }
    if ((result.hasOwnProperty('business_name'))) {
      temps['Business Name'] = '--';
    }
    if ((result.hasOwnProperty('package'))) {
      temps['Plan'] = result['package'];
    }
    if ((result.hasOwnProperty('created_at'))) {
      temps['Signup Date'] = moment(result['created_at']).format('YYYY-MM-DD');
    }
    temps['Days In This Billing Cycle'] = '-';
    if ((result.hasOwnProperty('base_price'))) {
      temps['Amount To Be Billed'] = '--';
    }
    result_array.push(temps);
    agency_users.forEach((obj, key) => {
      const temp: any = {}
      if ((obj.hasOwnProperty('name'))) {
        temp['Agency Name'] = '--';
      }
      if ((obj.hasOwnProperty('business_name'))) {
        temp['Business Name'] = obj['business_name'];
      }
      if ((obj.hasOwnProperty('package'))) {
        temp['Plan'] = obj['package'];
      }
      if ((obj.hasOwnProperty('created_at'))) {
        temp['Signup Date'] = moment(obj['created_at']).format('YYYY-MM-DD');
      }
      if ((obj.hasOwnProperty('base_price'))) {
        temp['Days In This Billing Cycle'] = obj['billing_days'];
      }
      if ((obj.hasOwnProperty('base_price'))) {
        temp['Amount To Be Billed'] = '$' + obj['plan_amount'];
      }
      result_array.push(temp);
    });

    result_array.forEach((row, idx) => {
      const keys = Object.keys(row);
      keys.forEach((key) => {
        if (headers.indexOf(key) === -1) {
          headers.push(key);
        }
      });
    });

    result_array.forEach((row, idx) => {
      headers.forEach((header, id) => {
        final_rows[idx] = final_rows[idx] || {};
        final_rows[idx][header] = final_rows[idx][header] || '';
        final_rows[idx][header] = this.getObject(header, row);
      });
    });
    const ngxcsv_options: any = {
      // fieldSeparator: ',',
      // quoteStrings: '"',
      // decimalseparator: '.',
      // showLabels: true,
      // showTitle: true,
      // title: 'Your title',
      // useBom: true,
      // noDownload: false,
      removeEmptyValues: false,
      headers: headers
    };
    const values_ngxcsv = new ngxCsv(result_array, 'BillingReport', ngxcsv_options);
    return;
  }

  // openEditModal(crm_contact_id) {
  //   this.crmService.param_id = crm_contact_id;
  //   this.crmService.user_type = 'ADMIN';
  //   this.modalRef = this.modalService.show(CrmEditContactModalComponent, this.config_invite_users);
  //   this.modalService.onHide.subscribe(result => {
  //     this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
  //       this.rows = response.data;
  //       this.counts = response.counts;
  //       this.page_no = 0;
  //       this.parameters.pagination.offset = 0;
  //     }, err => {
  //       console.log(err.message);
  //     });
  //   });
  // }

  // openAddModal() {
  //   this.crmService.user_type = 'ADMIN';
  //   this.modalRef = this.modalService.show(CrmAddContactModalComponent, this.config_invite_users);
  //   this.modalService.onHide.subscribe(result => {
  //     this.crmService.user_type = '';
  //     this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
  //       this.rows = response.data;
  //       this.counts = response.counts;
  //       this.page_no = 0;
  //       this.parameters.pagination.offset = 0;
  //     }, err => {
  //       console.log(err.message);
  //     });
  //   });
  // }

  // openBulkImportModal() {
  //   this.crmService.user_type = 'ADMIN';
  //   this.modalRef = this.modalService.show(CrmAdminBulkContactModalComponent, this.config_invite_users);
  //   this.modalService.onHide.subscribe(result => {
  //     this.crmService.user_type = '';
  //     this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
  //       this.rows = response.data;
  //       this.counts = response.counts;
  //       this.page_no = 0;
  //       this.parameters.pagination.offset = 0;
  //     }, err => {
  //       console.log(err.message);
  //     });
  //   });
  // }

  // downloadSampleFile() {
  //   this.isSubmitting = true;
  //   this.crmService.downloadSampleCRMFile().subscribe((response: any) => {
  //     window.open(response.data);
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

  // exportCRMContactFile() {
  //   const path = environment.site_addr + '/site/export_all_contact_file';
  //   window.open(path, '_blank');
  // }
}
