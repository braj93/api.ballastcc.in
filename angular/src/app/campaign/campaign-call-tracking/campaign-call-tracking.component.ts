import {Component, OnInit, ViewChild} from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { TabsetComponent, TabDirective } from 'ngx-bootstrap/tabs';
@Component({
  selector: 'app-campaign-call-tracking',
  templateUrl: './campaign-call-tracking.component.html',
  styleUrls: ['./campaign-call-tracking.component.css']
})
export class CampaignCallTrackingComponent implements OnInit {
  disableSwitching: boolean = false;
  // @ViewChild('tabset') tabset: TabsetComponent;
  // @ViewChild('first') first: TabDirective;
  // @ViewChild('second') second: TabDirective;

  @ViewChild('staticTabs', { static: false }) staticTabs: TabsetComponent;
  // mobNumberPattern = "^((\\+91-?)|0)?[0-9]{10}$";
  mobNumberPattern = "[0-9 ]{10}$";
  public isEdit : boolean = false;
  public isSubmitting : boolean = false;
  public is_disabled_one : boolean = false;
  public is_disabled_two : boolean = false;
  public is_disabled_three : boolean = false;
  public detail:any;
  public current_url:any;
  public submitFrom : FormGroup;
  public param_id:any;
  public tabs_css = 4;
  constructor(
    public fb: FormBuilder,
  	public userService: UserService,
  	public campaignService: CampaignService,
    public router: Router,
    public route: ActivatedRoute){
      this.submitFrom = this.fb.group({
      'number_name': ['', [Validators.required]],
      'destination_number': ['', [Validators.required, Validators.pattern("[0-9 ]{10}")]],
      'number_type': ['DEFAULT', Validators.required],
      'area_code': [''],
    });
    this.current_url = this.router.url;
  }

  isDisabled(){
    if(this.isEdit){
      return false;
    }
    return false;
  }

  ngOnInit(): void {
    this.userService.page_title = "Call Tracking Numbers";
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.route.data.subscribe((data:any) => {
      let result = data['detail'];
      this.detail = result.data;
      let tracker_detail = data['tracker_detail'];
      if (Object.keys(tracker_detail.data).length > 0) {
        this.isEdit = true;
        this.setForm(tracker_detail.data);
      }
      // tslint:disable-next-line:forin
      for (var i in this.detail) {
        if (this.detail[i] === 'YES') {
          this.detail[i] = true;
        }
        if (this.detail[i] === 'NO') {
          this.detail[i] = false;
        }
      }
      const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
      this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.detail.campaign_guid + '/edit';
      this.onFeatureChange();
    });
    
  }

  onFeatureChange() {
    const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
    this.campaignService.campaign_tabs[cr_index].is_tab = true;
    this.campaignService.campaign_tabs[cr_index].active = false;
    this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.param_id + '/edit';

    if (this.detail.is_landing_page === true) {
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
    if (this.detail.is_call_tracking_number === true) {
      const ct_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'calltracking');
      this.campaignService.campaign_tabs[ct_index].is_tab = true;
      this.campaignService.campaign_tabs[ct_index].active = true;
      this.campaignService.campaign_tabs[ct_index].url = '/user/campaigns/' + this.param_id + '/call-tracking-number';
    } else {
      const ct_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'calltracking');
      this.campaignService.campaign_tabs[ct_index].is_tab = false;
      this.campaignService.campaign_tabs[ct_index].active = false;
      this.campaignService.campaign_tabs[ct_index].url = '';
    }
    if (this.detail.is_qr_code === true) {
      const qr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'qrcode');
      this.campaignService.campaign_tabs[qr_index].is_tab = true;
      this.campaignService.campaign_tabs[qr_index].active = false;
      this.campaignService.campaign_tabs[qr_index].url = '/user/campaigns/' + this.param_id + '/qr-code';
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

  setForm(tracker_detail){
    let area_code_control = this.submitFrom.get('area_code');
    if(this.detail.number_type == 'AREACODE'){
      area_code_control.setValidators([Validators.required]);
      area_code_control.updateValueAndValidity();
    } else{
      area_code_control.setValue('');
      area_code_control.clearValidators();
      area_code_control.updateValueAndValidity();
    }
    if (tracker_detail.call_flow.destination_number && tracker_detail.call_flow.destination_number.includes("+1")) {
      tracker_detail.call_flow.destination_number = tracker_detail.call_flow.destination_number.replace('+1', '');
    }
    
    this.submitFrom = this.fb.group({
      number_name: [tracker_detail.name, Validators.required],
      'destination_number': [tracker_detail.call_flow.destination_number, [Validators.required, Validators.pattern("[0-9 ]{10}")]],
      number_type: [this.detail.number_type, [Validators.required]],
      area_code: [this.detail.area_code],
    });
    // console.log(tracker_detail);
  }

  selectTab(tabId: any) {
    this.staticTabs.tabs[tabId].active = true;
  }

  areaCodeSelect(){
    let number_type_control = this.submitFrom.get('number_type').value;
    let area_code_control = this.submitFrom.get('area_code');
    if(number_type_control == 'AREACODE'){
      area_code_control.setValidators([Validators.required]);
      area_code_control.updateValueAndValidity();
    } else{
      area_code_control.setValue('');
      area_code_control.clearValidators();
      area_code_control.updateValueAndValidity();
    }
  }

  setData(){
    // console.log('Logs');
  }

  submitForm() {
    if (this.isEdit) {
      this.updateForm();
    } else {
      let form_values:any = this.submitFrom.value;
      if (form_values.number_type == 'DEFAULT') {
        form_values.area_code = form_values.destination_number.substring(0,3);
      }
      form_values.campaign_id = this.detail.campaign_guid;
      this.isSubmitting = true;
      this.campaignService.createCallTrackingNumber(form_values).subscribe((response: any) => {
        let data = response.data;
        this.isSubmitting = false;
        this.userService.alerts.push({
          type: 'success',
          msg: response.message,
          timeout: 4000
        });
        if (this.detail.is_landing_page) {
          this.router.navigate(['/user/campaigns/' + this.detail.campaign_guid + '/templates']);
        } else if (this.detail.is_qr_code) {
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
  }

  updateForm() {
    let form_values:any = this.submitFrom.value;
    if (form_values.number_type == 'DEFAULT') {
      form_values.area_code = form_values.destination_number.substring(0,3);
    }
    form_values.campaign_id = this.detail.campaign_guid;
    this.isSubmitting = true;
    this.campaignService.updateTrackingNumber(form_values).subscribe((response: any) => {
      let data = response.data;
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      // this.router.navigate(['/user/campaigns/', this.detail.campaign_guid, 'view']);
      if (this.detail.is_landing_page) {
        this.router.navigate(['/user/campaigns/' + this.detail.campaign_guid + '/templates']);
      } else if (this.detail.is_qr_code) {
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

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }
}
