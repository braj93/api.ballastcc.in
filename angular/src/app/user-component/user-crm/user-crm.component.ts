import {Component, ViewChild, OnInit, TemplateRef} from '@angular/core';
import { UserService, CrmService, ApiService } from '../../core';
import { CrmAddContactModalComponent, CrmEditContactModalComponent } from '../../shared';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import {ActivatedRoute} from '@angular/router';
import { environment } from '../../../environments/environment';
declare var $;

@Component({
  selector: 'app-user-crm',
  templateUrl: './user-crm.component.html',
  styleUrls: ['./user-crm.component.css']
})
export class UserCrmComponent implements OnInit{
  public selectedFile: File;
  public contact_file: any = '';
  public currentUser: any;
  public isFileSubmitting: boolean = false;
  public uploadedFiledata: any;
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
  public page_no: any = 0;
  public parameters:any = {
    user_id: "",
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
  public param_id: any;
  public contact_detail: any;
  public config = {
    backdrop: true,
    ignoreBackdropClick: false,
    class: 'modal-md'
  };
  constructor(
    public apiService: ApiService,
      public userService: UserService,
      public crmService: CrmService,
      public modalService: BsModalService,
      public route: ActivatedRoute,
    ){

  }

  ngOnInit(): void {
    this.userService.page_title = "CRM";
    this.userService.currentUser.subscribe(
      (userData) => {
      this.currentUser = userData;
    });
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.parameters.user_id = this.param_id;
    this.route.data.subscribe((data:any) => {
      let result = data['crm_contact'];
      this.rows = result.data;
      this.counts = result.counts;
    });
  }

  formatPhoneNumber(number) {
    const number_val = number.replace(/\D[^\.]/g, '');
    const str = '(' + number_val.slice(0, 3) + ') ' + number_val.slice(3, 6) + '-' + number_val.slice(6);
    return str;
  }

  setPage(event) {
    this.parameters.pagination.limit = event.pageSize;
    this.parameters.pagination.offset = event.offset * event.pageSize;
    this.crmService.getList(this.parameters).subscribe((response: any) => {
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
    this.crmService.getList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }



  getCrmContactByIdList(){
    // console.log(this.parameters, " -- --- -- params");
    this.crmService.getCrmContactByIdList(this.parameters).subscribe((response: any) => {
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

  openModal(){
      this.userService.param_id = this.param_id;
      this.modalRef = this.modalService.show(CrmAddContactModalComponent, this.config_invite_users);
      // this.userService.popupRef = this.modalRef;
      this.modalService.onHide.subscribe(result => {
        this.crmService.getList(this.parameters).subscribe((response: any) => {
          this.rows = response.data;
          this.counts = response.counts;
        }, err => {
          console.log(err.message);
        });
      });
  }

  openEditModal(crm_contact_id){
      this.crmService.param_id = crm_contact_id;
      this.modalRef = this.modalService.show(CrmEditContactModalComponent, this.config_invite_users);
      this.modalService.onHide.subscribe(result => {
        this.crmService.getList(this.parameters).subscribe((response: any) => {
          this.rows = response.data;
          this.counts = response.counts;
        }, err => {
          console.log(err.message);
        });
      });
  }

  hideModal(){
    this.userService.popupRef.hide();
  }

  onFileChanged(event) {
    this.selectedFile = event.target.files[0];
    if (this.selectedFile) {
      this.isFileSubmitting = true;
      this.apiService.postMultiPart(this.selectedFile, 'excel').subscribe(data => {
        this.uploadedFiledata = data.data;
        this.isFileSubmitting = false;
        this.crmService.getList(this.parameters).subscribe((response: any) => {
          this.rows = response.data;
          this.counts = response.counts;
        });
        this.contact_file = "";
        this.userService.alerts.push({
          type: 'success',
          msg: data.message,
          timeout:  4000
        });
      }, err => {
        this.contact_file = "";
        this.isFileSubmitting = false;
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
      });
    }
  }

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

  search(event){
    if(this.parameters.keyword == "") {
      this.page_no = 0;
    }
    this.parameters.pagination.offset = 0;
    this.crmService.getList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      this.page_no = 0;
      this.parameters.pagination.offset = 0;
    }, err => {
      console.log(err.message);
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

  openDetailsModal(template: TemplateRef<any>, detail) {
    this.contact_detail = detail;
    this.modalRef = this.modalService.show(template, this.config);
  }

  hideModalFromChild() {
    setTimeout(() => {
      this.modalRef.hide();
    }, 1000);
  }

  exportCRMContactFile(){
    let path = environment.site_addr + '/site/export_user_contact_file?id=' + this.currentUser.user_guid;
    window.open(path, "_blank");
  }
}