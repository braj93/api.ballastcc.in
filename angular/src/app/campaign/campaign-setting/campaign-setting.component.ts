import {Component, OnInit, ViewChild} from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { environment } from '../../../environments/environment';
import { Location } from '@angular/common';
@Component({
  selector: 'app-campaign-setting',
  templateUrl: './campaign-setting.component.html',
  styleUrls: ['./campaign-setting.component.css']
})
export class CampaignSettingComponent implements OnInit{
  public detail : any;
  public isSubmitting : boolean = false;
  public submitFrom : FormGroup;
  public param_id : any;
  public tabs_css = 4;
  constructor(
    private location: Location,
  	public userService: UserService,
  	public campaignService: CampaignService,
    public fb: FormBuilder,
    public route: ActivatedRoute,
    public router: Router){
      this.submitFrom = this.fb.group({
        'page_title': ['', [Validators.required]],
        'email_receiver': [''],
        'page_url': ['', [Validators.required]],
        'custom_page_script': [''],
        // 'qr_code_url': ['']
      });
    }

  ngOnInit(): void {
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    // tikisites.com/businessname/campaign
    this.userService.page_title = "Campaign Setting";
    this.route.data.subscribe((data:any) => {
      let result = data['detail'];
      this.detail = result.data;
    });
    if(this.detail.unique_string){
      let name = this.removeSpace(this.detail.campaign_name);
      let path = environment.site_addr + '/' + name + '/' + this.detail.unique_string;
      this.submitFrom.get('page_url').setValue(path);
      // this.submitFrom.get('qr_code_url').setValue(path);
    }
    this.submitFrom.get('page_title').setValue(this.detail.page_title);
    this.submitFrom.get('email_receiver').setValue(this.detail.email_receiver);
    this.submitFrom.get('custom_page_script').setValue(this.detail.custom_page_script);
    const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
    this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.detail.campaign_guid + '/edit';
    this.onFeatureChange();
  }

  onFeatureChange() {
    const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
    this.campaignService.campaign_tabs[cr_index].is_tab = true;
    this.campaignService.campaign_tabs[cr_index].active = false;
    this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.param_id + '/edit';

    if (this.detail.is_landing_page === 'YES') {
      const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
      this.campaignService.campaign_tabs[lp_index].is_tab = true;
      this.campaignService.campaign_tabs[lp_index].active = true;
      if (this.detail.is_landing_page === 'YES' && !this.detail.template_values) {
        this.campaignService.campaign_tabs[lp_index].url = '/user/campaigns/' + this.param_id + '/templates';
      } else if (this.detail.is_landing_page === 'YES' && this.detail.template_values) {
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
      this.campaignService.campaign_tabs[qr_index].active = false;
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

  // generateQrCode(){
  //   let form_values:any = this.submitFrom.value
  //   form_values.campaign_id = this.detail.campaign_guid;
  //   this.isSubmitting = true;
  //   this.campaignService.generateQrCode({campaign_id: form_values.campaign_id, qr_code_url:form_values.qr_code_url}).subscribe((response: any) => {
  //     this.detail = response.data;
  //     this.isSubmitting = false;
  //     this.userService.alerts.push({
  //       type: 'success',
  //       msg: response.message,
  //       timeout: 4000
  //     });
  //     },
  //     err => {
  //       this.userService.alerts.push({
  //         type: 'danger',
  //         msg: err.message,
  //         timeout: 4000
  //       });
  //       this.isSubmitting = false;
  //   });
  // }

  removeSpace(str){
    str = str.toLowerCase();
    str = str.replace(/ /g, "-");
    return str;
  }

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

  submitForm() {
    let form_values:any = this.submitFrom.value
    form_values.campaign_id = this.detail.campaign_guid;
    this.isSubmitting = true;
    this.campaignService.updateCampaignSetting(form_values).subscribe((response: any) => {
      let data = response.data;
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
        if (this.detail.is_qr_code == 'YES') {
          this.router.navigate(['/user/campaigns/' + this.detail.campaign_guid + '/qr-code']);
        } else {
          this.router.navigate(['/user/campaigns/', this.detail.campaign_guid, 'view']);
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

  goToBack(){
      this.location.back();
  }
  
}