import {Component, ViewChild, OnInit} from '@angular/core';
import { UserService, CrmService } from '../../core';
import { CrmAddContactModalComponent, CrmEditContactModalComponent, CrmAdminBulkContactModalComponent } from '../../shared';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { environment } from '../../../environments/environment';
declare var $;

@Component({
  selector: 'app-crm',
  templateUrl: './crm.component.html',
  styleUrls: ['./crm.component.css']
})
export class CrmComponent implements OnInit{
  public modalRef: BsModalRef;
  public config_invite_users = {
    backdrop: true,
    ignoreBackdropClick: true,
    class : 'modal-lg'
  };
  public isSubmitting: boolean = false;
  public alerts: any = [];
  public rows: any = [];
  public loading: boolean = false;
  public counts: any;
  public currentUser: any;
  public page_no: any = 0;
  public parameters:any = {
    keyword: "",
    pagination: {
        offset: 0,
        limit: 10
      },
    sort_by:{
      column_name: '',
      order_by: '',
    },
    filter:""
  };
  constructor(
    public userService: UserService,
    public modalService: BsModalService,
    public crmService: CrmService
    ){
  }

  ngOnInit(): void {
    this.userService.currentUser.subscribe(
      (userData) => {
      this.currentUser = userData;
    });
    this.userService.page_title = "CRM";
    this.getCrmContacts();
  }

  formatPhoneNumber(number) {
    const number_val = number.replace(/\D[^\.]/g, '');
    const str = '(' + number_val.slice(0, 3) + ') ' + number_val.slice(3, 6) + '-' + number_val.slice(6);
    return str;
  }

  setPage(event) {
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  onSort(event){
    this.page_no = event.offset;
    this.parameters.sort_by.column_name = event.sorts[0].prop == 'name' ? 'first_name' : event.sorts[0].prop;
    this.parameters.sort_by.order_by = event.sorts[0].dir;
    // console.log("this.parameters", this.parameters);
    this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }


  getCrmContacts(){
    this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
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

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

  search(event){
    if(this.parameters.keyword == "") {
      this.page_no = 0;
    }
    this.parameters.pagination.offset = 0;
    this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
    });
  }

  openEditModal(crm_contact_id){
    this.crmService.param_id = crm_contact_id;
    this.crmService.user_type = 'ADMIN';
    this.modalRef = this.modalService.show(CrmEditContactModalComponent, this.config_invite_users);
    this.modalService.onHide.subscribe(result => {
      this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
        this.rows = response.data;
        this.counts = response.counts;
        this.page_no = 0;
        this.parameters.pagination.offset = 0;
      }, err => {
        console.log(err.message);
      });
    });
  }

  openAddModal(){
    this.crmService.user_type = 'ADMIN';
    this.modalRef = this.modalService.show(CrmAddContactModalComponent, this.config_invite_users);
    this.modalService.onHide.subscribe(result => {
      this.crmService.user_type = '';
      this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
        this.rows = response.data;
        this.counts = response.counts;
        this.page_no = 0;
        this.parameters.pagination.offset = 0;
      }, err => {
        console.log(err.message);
      });
    });
  }

  openBulkImportModal(){
    this.crmService.user_type = 'ADMIN';
    this.modalRef = this.modalService.show(CrmAdminBulkContactModalComponent, this.config_invite_users);
    this.modalService.onHide.subscribe(result => {
      this.crmService.user_type = '';
      this.crmService.getCrmContacts(this.parameters).subscribe((response: any) => {
        this.rows = response.data;
        this.counts = response.counts;
        this.page_no = 0;
        this.parameters.pagination.offset = 0;
      }, err => {
        console.log(err.message);
      });
    });
  }

  downloadSampleFile(){
    this.isSubmitting = true; 
    this.crmService.downloadSampleCRMFile().subscribe((response: any) => {
      window.open(response.data);
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

  exportCRMContactFile(){
    let path = environment.site_addr + '/site/export_all_contact_file';
    window.open(path, "_blank");
  }
}