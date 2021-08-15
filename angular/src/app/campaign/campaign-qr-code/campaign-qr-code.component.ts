import { Component, OnInit, ViewChild } from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { environment } from '../../../environments/environment';
import { Location } from '@angular/common';
@Component({
  selector: 'app-campaign-qr-code',
  templateUrl: './campaign-qr-code.component.html',
  styleUrls: ['./campaign-qr-code.component.css']
})
export class CampaignStepTwoComponent implements OnInit {
  public isSubmitting: boolean = false;
  public submitFrom: FormGroup;
  public bsConfig: Partial<BsDatepickerConfig>;
  public bsRangeConfig: Partial<BsDatepickerConfig>;
  public minDate: any = '';
  public currentDate: any;
  public default_date: any;

  public detail: any;
  public current_url: any;
  public param_id: any;
  public tabs_css = 4;
  constructor(
    private location: Location,
    public userService: UserService,
    public campaignService: CampaignService,
    public fb: FormBuilder,
    public router: Router,
    public route: ActivatedRoute) {
    this.submitFrom = this.fb.group({
      'qr_code_url': ['', [Validators.required]]
    });
    this.current_url = this.router.url;
  }

  ngOnInit(): void {
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.route.data.subscribe((data: any) => {
      const result = data['detail'];
      this.detail = result.data;
      const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
      this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.detail.campaign_guid + '/edit';
      this.onFeatureChange();

      if (this.detail.is_qr_code === 'YES') {
        this.userService.page_title = 'QR Code';
      }
      if (this.detail.is_qr_code === 'YES' && this.detail.is_landing_page === 'NO') {
        const page_url = this.removeURLParameter(this.detail.page_url, 'qr');
        this.submitFrom.get('qr_code_url').setValue(page_url);
      } else {
        const url = this.removeURLParameter(this.detail.qr_code_url, 'qr');
        this.submitFrom.get('qr_code_url').setValue(url);
      }
      // this.submitFrom.get('qr_code_url').setValue(this.detail.qr_code_url);

      if (this.detail.unique_string && this.detail.is_landing_page === 'YES') {
        const name = this.removeSpace(this.detail.campaign_name);
        const path = environment.site_addr + '/' + name + '/' + this.detail.unique_string;
        this.submitFrom.get('qr_code_url').setValue(path);
      }
    });
  }

  onFeatureChange() {
    const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
    this.campaignService.campaign_tabs[cr_index].is_tab = true;
    this.campaignService.campaign_tabs[cr_index].active = false;
    this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.param_id + '/edit';

    if (this.detail.is_landing_page === 'YES') {
      const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
      this.campaignService.campaign_tabs[lp_index].is_tab = true;
      this.campaignService.campaign_tabs[lp_index].active = false;
      if (this.detail.is_landing_page && !this.detail.template_values) {
        this.campaignService.campaign_tabs[lp_index].url = '/user/campaigns/' + this.param_id + '/templates';
      } else if (this.detail.is_landing_page && this.detail.template_values) {
        this.campaignService.campaign_tabs[lp_index].url = '/user/campaigns/' + this.param_id + '/design';
      }
    } else {
      const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
      this.campaignService.campaign_tabs[lp_index].is_tab = false;
      this.campaignService.campaign_tabs[lp_index].active = false;
      this.campaignService.campaign_tabs[lp_index].url = '';
    }
    if (this.detail.is_call_tracking_number === 'YES') {
      const ct_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'calltracking');
      this.campaignService.campaign_tabs[ct_index].is_tab = true;
      this.campaignService.campaign_tabs[ct_index].active = false;
      this.campaignService.campaign_tabs[ct_index].url = '/user/campaigns/' + this.detail.campaign_guid + '/call-tracking-number';
    } else {
      const ct_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'calltracking');
      this.campaignService.campaign_tabs[ct_index].is_tab = false;
      this.campaignService.campaign_tabs[ct_index].active = false;
      this.campaignService.campaign_tabs[ct_index].url = '';
    }
    if (this.detail.is_qr_code === 'YES') {
      const qr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'qrcode');
      this.campaignService.campaign_tabs[qr_index].is_tab = true;
      this.campaignService.campaign_tabs[qr_index].active = true;
      this.campaignService.campaign_tabs[qr_index].url = '/user/campaigns/' + this.detail.campaign_guid + '/qr-code';
    } else {
      const qr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'qrcode');
      this.campaignService.campaign_tabs[qr_index].is_tab = false;
      this.campaignService.campaign_tabs[qr_index].active = false;
      this.campaignService.campaign_tabs[qr_index].url = '';
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

  removeURLParameter(url, parameter) {
    const urlparts = url.split('?');
    if (urlparts.length >= 2) {

      const prefix = encodeURIComponent(parameter) + '=';
      const pars = urlparts[1].split(/[&;]/g);

      // reverse iteration as may be destructive

      for (var i = pars.length; i-- > 0;) {
        // idiom for string.startsWith
        if (pars[i].lastIndexOf(prefix, 0) !== -1) {
          pars.splice(i, 1);
        }
      }

      url = urlparts[0] + '?' + pars.join('&');
      return url;
    } else {
      return url;
    }
  }
  generateQrCode() {

  }

  removeSpace(str) {
    str = str.toLowerCase();
    str = str.replace(/ /g, '-');
    return str;
  }

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }

  submitForm() {
    const form_values: any = this.submitFrom.value;
    form_values.campaign_id = this.detail.campaign_guid;
    this.isSubmitting = true;
    this.campaignService.generateQrCode(form_values).subscribe((response: any) => {
      this.detail = response.data;
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.router.navigate(['/user/campaigns/', this.detail.campaign_guid, 'view']);
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

  goToBack() {
    this.location.back();
  }
}
