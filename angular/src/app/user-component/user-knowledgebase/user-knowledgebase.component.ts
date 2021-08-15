import { Component, OnInit } from '@angular/core';
import { UserService, AdminService } from '../../core';
import { BsModalService } from 'ngx-bootstrap/modal';
import { ActivatedRoute } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser'
declare var $;

@Component({
  selector: 'app-user-knowledgebase',
  templateUrl: './user-knowledgebase.component.html',
  styleUrls: ['./user-knowledgebase.component.scss']
})
export class UserKnowledgebaseComponent implements OnInit {
  public categories_list: any = [];
  public category_id: any = '';
  public isSubmitting: any = false;
  public alerts: any = [];
  public rows: any = [];
  public loading: any = false;
  public counts: any;
  public page_no: any = 0;
  public parameters: any = {
    user_id: '',
    keyword: '',
    pagination: {
      offset: 0,
      limit: 6
    },
    sort_by: {
      column_name: '',
      order_by: '',
    },
    filters: {
      category: ''
    }
  };
  public param_id: any;
  public pages: any;
  public current_page: any = 1;
  public last_index: any;
  public currentUser: any;
  constructor(
    public userService: UserService,
    public adminService: AdminService,
    public modalService: BsModalService,
    public route: ActivatedRoute,
    public sanitizer: DomSanitizer
  ) {
    this.route.queryParams.subscribe(params => {
      this.category_id = params['category_id'];
      if (this.category_id) {
        this.parameters.filters.category = this.category_id;
      } else {
        this.parameters.filters.category = '';
      }
      this.filterCategory();
    });
    this.userService.currentUser.subscribe(
      (userData) => {
        this.currentUser = userData;
      }
    );
  }

  ngOnInit(): void {
    this.userService.page_title = 'Knowledge Base';
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.parameters.user_id = this.param_id;
    // this.route.data.subscribe((data:any) => {
    //   let result = data['user_knowledgebase'];
    //   this.rows = result.data;
    //   this.counts = result.counts;
    // });

    // FOR PAGINATION
    // let number = this.counts / this.parameters.pagination.limit;
    // let number = Math.ceil(this.counts / this.parameters.pagination.limit);
    // console.log("number", number);

    // this.pages = Array.from(Array(number).keys());
    // this.last_index = this.pages.length;

    this.getCategories();
  }

  setPage(page_no) {
    this.current_page = page_no;
    this.parameters.pagination.offset = (page_no - 1) * this.parameters.pagination.limit;
    this.adminService.userKnowledgebaseList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  paginationAction(action) {
    this.current_page = action === 'next' ? this.current_page + 1 : this.current_page - 1;
    this.parameters.pagination.offset = (this.current_page - 1) * this.parameters.pagination.limit;
    this.adminService.userKnowledgebaseList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  search(event) {
    if (this.parameters.keyword === '') {
      this.current_page = 1;
      this.parameters.pagination.offset = (this.current_page - 1) * this.parameters.pagination.limit;
    }
    this.parameters.pagination.offset = 0;
    this.adminService.userKnowledgebaseList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;

      const number = Math.ceil(this.counts / this.parameters.pagination.limit);
      this.pages = Array.from(Array(number).keys());
      this.current_page = 1;
      this.last_index = this.pages.length;
    }, err => {
      console.log(err.message);
    });

  }

  formattedText(str) {
    return str.slice(0, 153) + ' .....';
  }

  getCategories() {
    this.adminService.categoriesList().subscribe((response: any) => {
      this.categories_list = response.data;
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  filterCategory() {
    if (this.parameters.filters.category === '') {
      this.current_page = 1;
      this.parameters.pagination.offset = (this.current_page - 1) * this.parameters.pagination.limit;
    }
    this.parameters.pagination.offset = 0;
    this.adminService.userKnowledgebaseList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
      const number = Math.ceil(this.counts / this.parameters.pagination.limit);
      this.pages = Array.from(Array(number).keys());
      this.current_page = 1;
      this.last_index = this.pages.length;
    }, err => {
      console.log(err.message);
    });
  }
}
