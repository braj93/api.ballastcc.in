import { Component, OnInit } from '@angular/core';
import { CampaignService, UserService } from '../../core';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-user-dashboard',
  templateUrl: './user-dashboard.component.html',
  styleUrls: ['./user-dashboard.component.css']
})
export class UserDashboardComponent implements OnInit {
  public details: any;
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
  public currentUser: any;
  constructor(
    public route: ActivatedRoute,
    public userService: UserService,
    public campaignService: CampaignService
  ) { }
  ngOnInit() {
    this.userService.currentUser.subscribe(
      (userData) => {
      this.currentUser = userData;
      this.userService.page_title = this.currentUser.business_name + ' Dashboard';
    });
    this.route.data.subscribe((data: any) => {
      const result = data['dashboard_details'];
      this.details = result.data;
    });
    this.getLandingPageRanking();
  }

  getLandingPageRanking() {
    this.campaignService.getLandingPageRanking(this.parameters).subscribe((response: any) => {
      const results = response.data || [];
      results.forEach((row, idx) => {
        if (row.campaign_guid) {
          this.rows.push(row);
        }
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
