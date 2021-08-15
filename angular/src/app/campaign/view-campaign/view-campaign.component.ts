import { Component, OnInit, OnDestroy } from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { Router, ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-view-campaign',
  templateUrl: './view-campaign.component.html',
  styleUrls: ['./view-campaign.component.css']
})
export class ViewCampaignComponent implements OnInit, OnDestroy {
  public isSubmitting: boolean = false;
  public detail: any;
  public report_detail: any;
  public param_id: any;
  constructor(
    private location: Location,
    public userService: UserService,
    public campaignService: CampaignService,
    public router: Router,
    public route: ActivatedRoute) {
      this.route.params.subscribe(params => {
        this.param_id = params.id;
      });
    }

  ngOnInit(): void {
    this.userService.page_title = "View Campaign";
    this.route.data.subscribe((data: any) => {
      let result = data['detail'];
      // this.report_detail = data['report_detail'];
      this.detail = result.data;
      for (var i in this.detail) {
        if (this.detail[i] === 'YES') {
          this.detail[i] = true;
        }
        if (this.detail[i] === 'NO') {
          this.detail[i] = false;
        }
      }
    });
    this.getCampaignReportDetailsById();
  }

  formatPhoneNumber(number) {
    const number_val = number.replace(/\D[^\.]/g, '');
    const str = '(' + number_val.slice(0, 3) + ') ' + number_val.slice(3, 6) + '-' + number_val.slice(6);
    return str;
  }

  getCampaignReportDetailsById() {
    this.campaignService.getCampaignReportDetailsById({campaign_id : this.param_id}).subscribe((response: any) => {
      this.report_detail = response;
      // this.report_detail.call_tracking.total_calls = 5
      // this.report_detail.call_tracking.total_durations = '10m0s'
      // this.report_detail.call_tracking.average_duration = '105s'
      // this.report_detail.qr_code.total_scans = 5
      // this.report_detail.qr_code.unique_scans = 1
      // this.report_detail.qr_code.leads = 1
      // this.report_detail.landing_page.total_pageviews = 5
      // this.report_detail.landing_page.unique_visitors = 5
      // this.report_detail.landing_page.leads = 2
      this.isSubmitting = true;
    }, err => {
      // this.userService.alerts.push({
      //   type: 'danger',
      //   msg: err.message,
      //   timeout: 4000
      // });
      this.isSubmitting = true;
    });
  }

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }

  goToBack() {
    this.location.back();
  }

  updateCampaignStatus(status) {
    this.campaignService.updateCampaignStatus({campaign_id : this.param_id, status : status}).subscribe((response: any) => {
      this.detail.status = response.data.status;
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
