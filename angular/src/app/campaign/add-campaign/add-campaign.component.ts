import {Component, OnInit, OnDestroy} from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { Router } from '@angular/router';
import * as moment from 'moment';
import {BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { Location } from '@angular/common';
@Component({
  selector: 'app-add-campaign',
  templateUrl: './add-campaign.component.html',
  styleUrls: ['./add-campaign.component.css']
})
export class AddCampaignComponent implements OnInit, OnDestroy {
  public isSubmitting: any = false;
  public canAddCampaign: any = false;
  public submitFrom: FormGroup;
  public bsConfig: Partial<BsDatepickerConfig>;
  public bsRangeConfig: Partial<BsDatepickerConfig>;
  public minDate: any = '';
  public currentDate: any;
  public default_date: any;
  public is_admin: any = false;
  public user_list: any = [];
  public currentUser: any = '';
  public current_url: any = '';
  public tabs_css = 4;
  constructor(
    private location: Location,
    public userService: UserService,
    public campaignService: CampaignService,
    public fb: FormBuilder,
    public router: Router) {
      this.current_url = this.router.url;
      this.submitFrom = this.fb.group({
        'campaign_name': ['', [Validators.required]],
        'campaign_goal': ['', [Validators.required]],
        'campaign_live_date': ['', [Validators.required]],
        'is_landing_page': [true, [Validators.required]],
        'is_qr_code': [true, [Validators.required]],
        'is_call_tracking_number': [false, [Validators.required]],
        'user_id': ['']
      });
      this.default_date = new Date();
      this.currentDate = moment();
      this.minDate = new Date(this.currentDate)
      this.bsConfig = Object.assign({}, { containerClass: 'theme-dark-blue', dateInputFormat: 'MM/DD/YYYY', minDate: this.minDate } );

      this.userService.currentUser.subscribe(
        (userData) => {
        this.currentUser = userData;
      });
      this.onFeatureChange();
    }

  ngOnInit(): void {
    this.userService.page_title = 'Create Campaign';
    if (this.currentUser.user_type === 'ADMIN') {
      this.is_admin = true;
      this.getUsersList();
    }
  }

  getUsersList() {
    const user_id_control = this.submitFrom.get('user_id');
    user_id_control.setValidators([Validators.required]);
    user_id_control.updateValueAndValidity();
    this.userService.getUserList({type: 'CAMPAIGN'}).subscribe((response: any) => {
      this.user_list = response.data;
    }, err => {
      console.log(err.message);
    });
  }

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }

  onValueChange(value: Date): void {
    if (value) {
      const broadcast_sent_date = moment(this.default_date).format('YYYY-MM-DD');
      const campaign_live_date_control = this.submitFrom.get('campaign_live_date').setValue(broadcast_sent_date);
    }
  }

  submitForm() {
    const form_values: any = this.submitFrom.value
    let is_selcted = true;
    // tslint:disable-next-line:forin
    for (var i in form_values) {
      if (form_values[i] === true) {
        form_values[i] = 'YES';
        is_selcted = false;
      }
      if (form_values[i] === false) {
        form_values[i] = 'NO';
      }
    }
    if (is_selcted) {
      this.userService.alerts.push({
        type: 'danger',
        msg: 'Please Select Campaign Features',
        timeout: 2000
      });
    return;
    } else {
      this.isSubmitting = true;
      this.campaignService.addCampaign(form_values).subscribe((response: any) => {
        const data = response.data;
        this.isSubmitting = false;
        this.userService.alerts.push({
          type: 'success',
          msg: response.message,
          timeout: 4000
        });
        if (form_values.is_call_tracking_number === 'YES') {
          this.router.navigate(['/user/campaigns/' + data.campaign_guid + '/call-tracking-number']);
        } else if (form_values.is_landing_page === 'YES') {
          this.router.navigate(['/user/campaigns/' + data.campaign_guid + '/templates']);
        } else {
          this.router.navigate(['/user/campaigns/' + data.campaign_guid + '/qr-code']);
        }
      },
      err => {
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
        this.isSubmitting = false;
      });
    }
  }

  goToBack() {
    this.location.back();
  }

  onFeatureChange() {
    this.campaignService.campaign_tabs[0].active = true;
    const is_landing_page = this.submitFrom.get('is_landing_page').value;
    const is_call_tracking_number = this.submitFrom.get('is_call_tracking_number').value;
    const is_qr_code = this.submitFrom.get('is_qr_code').value;
    if (is_landing_page) {
      const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
      this.campaignService.campaign_tabs[lp_index].is_tab = true;
      this.campaignService.campaign_tabs[lp_index].active = false;
      this.campaignService.campaign_tabs[lp_index].url = '';
      // if (lp_index === -1) {
      //   this.campaignService.campaign_tabs.push({
      //     'tilte': 'Landing Page',
      //     'value': 'landingpage',
      //     'url': '',
      //     'active': true
      //   });
      // }
      // else {
      //   this.campaignService.campaign_tabs.splice(lp_index, 1);
      // }
    } else {
      const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
      this.campaignService.campaign_tabs[lp_index].is_tab = false;
      this.campaignService.campaign_tabs[lp_index].active = false;
      this.campaignService.campaign_tabs[lp_index].url = '';
      // if (lp_index !== -1) {
      //   this.campaignService.campaign_tabs.splice(lp_index, 1);
      // }
    }
    if (is_call_tracking_number) {
      const ct_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'calltracking');
      this.campaignService.campaign_tabs[ct_index].is_tab = true;
      this.campaignService.campaign_tabs[ct_index].active = false;
      this.campaignService.campaign_tabs[ct_index].url = '';
      // if (ct_index === -1) {
      //   this.campaignService.campaign_tabs.push({
      //     'tilte': 'Call Tracking',
      //     'value': 'calltracking',
      //     'url': '',
      //     'active': true
      //   });
      // }
      // else {
      //   this.campaignService.campaign_tabs.splice(ct_index, 1);
      // }
    } else {
      const ct_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'calltracking');
      this.campaignService.campaign_tabs[ct_index].is_tab = false;
      this.campaignService.campaign_tabs[ct_index].active = false;
      this.campaignService.campaign_tabs[ct_index].url = '';
      // if (ct_index !== -1) {
      //   this.campaignService.campaign_tabs.splice(ct_index, 1);
      // }
    }
    if (is_qr_code) {
      const qr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'qrcode');
      this.campaignService.campaign_tabs[qr_index].is_tab = true;
      this.campaignService.campaign_tabs[qr_index].active = false;
      this.campaignService.campaign_tabs[qr_index].url = '';
      // if (qr_index === -1) {
      //   this.campaignService.campaign_tabs.push({
      //     'tilte': 'Qr Code',
      //     'value': 'qrcode',
      //     'url': '',
      //     'active': true
      //   });
      // }
      // else {
      //   this.campaignService.campaign_tabs.splice(qr_index, 1);
      // }
    } else {
      const qr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'qrcode');
      this.campaignService.campaign_tabs[qr_index].is_tab = false;
      this.campaignService.campaign_tabs[qr_index].active = false;
      this.campaignService.campaign_tabs[qr_index].url = '';
      // if (qr_index !== -1) {
      //   this.campaignService.campaign_tabs.splice(qr_index, 1);
      // }
    }
    this.getTabsLength();
  }

  getTabsLength() {
    const tabs_array = [];
    this.campaignService.campaign_tabs.forEach((tab) => {
      if (tab.is_tab) {
        tabs_array.push(tab);
      }
    });
    if (tabs_array.length === 1) {
      // console.log(tabs_array.length, ' =1');
      this.tabs_css = 1;
      return 1;
    }
    if (tabs_array.length === 2) {
      // console.log(tabs_array.length, ' =2');
      this.tabs_css = 2;
      return 2;
    }
    if (tabs_array.length === 3) {
      this.tabs_css = 3;
      return 3;
    }
    this.tabs_css = 4;
    return 4;
  }

  onUserChange() {
    const user_id = this.submitFrom.get('user_id').value;
    let canedit = false;
    this.user_list.forEach((user) => {
      if (user.user_guid === user_id && user.can_add_campaign === 'NO') {
        canedit = true;
      };
    });
    this.canAddCampaign =  canedit;
  }
}
