import { Component, OnInit,TemplateRef } from '@angular/core';
import { AdminService } from '../core';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { Router } from '@angular/router';
@Component({
  selector: 'app-student',
  templateUrl: './student.component.html',
  styleUrls: ['./student.component.css']
})
export class StudentComponent implements OnInit {
  public isSubmitting: boolean = false;
  public canAddCampaignDisable: boolean = false;
  public alerts: any = [];
  public modalRef: BsModalRef;
  public rows: any = [];
  public is_admin: boolean = false;
  public loading: boolean = false;
  public counts: any;
  public page_no: any = 0;
  public parameters: any = {
    keyword: '',
    pagination: {
        offset: 0,
        limit: 3
      },
    sort_by: {
      column_name: '',
      order_by: '',
    },
    filters: {
      status: '',
    }
  };
  public pages: any;
  public current_page: any = 1;
  public last_index: any;
  public currentUser: any;
  public student_detail: any;
  public config = {
    backdrop: true,
    ignoreBackdropClick: false,
    class: 'modal-lg'
  };
  constructor(
    public router: Router,
    public adminService: AdminService,
    public modalService: BsModalService,
  ) { 
    this.adminService.currentUser.subscribe(
      (userData) => {
      this.currentUser = userData;
    });    
  }

  ngOnInit(): void {

    this.adminService.page_title = 'Students List';
    this.getStudentsList();
  }
  getStudentsList() {
    this.adminService.getStudentsList(this.parameters).subscribe((response: any) => {
      this.isSubmitting = true;
      this.rows = response.data;
      console.log(this.rows);
      this.counts = response.counts;
      this.isSubmitting = false;
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

  formattedText(str:string) {
    return str.slice(0, 153) + ' .....';
  }
  goToCreateStudent(){
    
    this.router.navigate(['/admin/student/add']);
  }
  openDetailsModal(template: TemplateRef<any>, detail:any) {
    this.student_detail = detail;
    this.modalRef = this.modalService.show(template, this.config);
  }

}
