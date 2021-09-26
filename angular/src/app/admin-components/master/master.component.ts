import { Component, OnInit } from '@angular/core';
import { AdminService } from '../../core';
import { ActivatedRoute } from '@angular/router';
@Component({
  selector: 'app-master',
  templateUrl: './master.component.html',
  styleUrls: ['./master.component.scss']
})
export class MasterComponent implements OnInit {
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
  public batches_list: any = [];
  public classes_list: any = [];
  public boards_list: any = [];
  public subjects_list: any = [];
  public pages: any;
  public current_page: any = 1;
  public last_index: any;
  public currentUser: any;
  constructor(public route: ActivatedRoute,
    public adminService: AdminService,) { }

  ngOnInit(): void {
    this.adminService.page_title = 'Master List';
    this.route.data.subscribe((data: any) => {
      
      const result = data['master_details'];
      this.details = result.data;
      this.batches_list =this.details.batches_data;
      this.classes_list =this.details.classes_data;
      this.boards_list =this.details.boards_data;
      this.subjects_list =this.details.subject_data;
      console.log(this.details);
    });
  }

}
