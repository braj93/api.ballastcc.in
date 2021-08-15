import {Component, ViewChild, OnInit} from '@angular/core';
import { UserService, CampaignService } from '../core';
import { Router } from '@angular/router';
declare var $;

@Component({
  selector: 'app-campaign',
  templateUrl: './campaign.component.html',
  styleUrls: ['./campaign.component.css']
})
export class CampaignComponent implements OnInit {
  public isSubmitting: boolean = false;
  public canAddCampaignDisable: boolean = false;
  public alerts: any = [];
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
  constructor(
    public router: Router,
    public userService: UserService,
    public campaignService: CampaignService
    ) {
      this.userService.currentUser.subscribe(
        (userData) => {
        this.currentUser = userData;
      });
    }

  ngOnInit(): void {

    if (this.currentUser.user_type === 'ADMIN') {
      this.is_admin = true;
    }
    this.userService.page_title = 'Campaigns';
    this.getCampaignList();
  }

  goToCreateCampaign() {
    this.router.navigate(['/user/campaigns/create']);
  }

  isNewCampaignDisabled() {
    if (this.currentUser.can_add_campaign === 'NO') {
      this.canAddCampaignDisable = true;
      return true;
    } else {
      this.canAddCampaignDisable = false;
      return false;
    }

    // console.log(this.currentUser.user_role);
    // console.log(this.rows.length);
    // console.log(this.counts);
    // if ((this.currentUser.user_role === 'USER_INDIVIDUAL_TEAM' || this.currentUser.user_role === 'USER_INDIVIDUAL_OWNER') && this.currentUser.plan_name === 'ESSENTIAL' && this.counts >= 1) {
    //   this.canAddCampaignDisable = true;
    //   return true;
    // } else if ( (this.currentUser.user_role === 'USER_INDIVIDUAL_TEAM' || this.currentUser.user_role === 'USER_INDIVIDUAL_OWNER') && this.currentUser.plan_name === 'PRO' && this.counts >= 5) {
    //   this.canAddCampaignDisable = true;
    //   return true;
    // } else {
    //   this.canAddCampaignDisable = false;
    //   return false;
    // }
  }

  getCampaignList() {
    this.campaignService.getCampaignList(this.parameters).subscribe((response: any) => {
      this.isSubmitting = true;
      this.rows = response.data;
      this.counts = response.counts;
      this.isSubmitting = false;
      // FOR PAGINATION
      // let number: any = this.counts / this.parameters.pagination.limit;
      const number: any = Math.ceil(this.counts / this.parameters.pagination.limit);

      this.pages = Array.from(Array(number).keys());
      this.last_index = this.pages.length;
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.isSubmitting = false;
    });
  }

  setPage(page_no) {
    this.current_page = page_no;
    this.parameters.pagination.offset = (page_no - 1) * this.parameters.pagination.limit;
    this.campaignService.getCampaignList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  paginationAction(action) {
    this.current_page = action === 'next' ? this.current_page + 1 : this.current_page - 1;
    this.parameters.pagination.offset = (this.current_page - 1) * this.parameters.pagination.limit;
    this.campaignService.getCampaignList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  onSort(event) {

  }
  search(event) {
    if (this.parameters.keyword === '') {
      this.page_no = 0;
    }
    this.parameters.pagination.offset = 0;
    this.campaignService.getCampaignList(this.parameters).subscribe((response: any) => {
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

  formattedText(str) {
    return str.slice(0, 153) + ' .....';
  }
}
