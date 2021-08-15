import {Component, OnInit, ViewChild} from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import * as moment from 'moment';
import {BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { Location } from '@angular/common';
@Component({
  selector: 'app-edit-campaign',
  templateUrl: './edit-campaign.component.html',
  styleUrls: ['./edit-campaign.component.css']
})
export class EditCampaignComponent implements OnInit{
  public isSubmitting : boolean = false;
  public submitFrom : FormGroup;
  public bsConfig: Partial<BsDatepickerConfig>;
  public bsRangeConfig: Partial<BsDatepickerConfig>;
  public minDate:any = '';
  public currentDate:any;
  public default_date:any;
  public detail:any;
  public param_id:any;
  public current_url: any = '';
  public tabs_css = 4;
  constructor(
    private location: Location,
  	public userService: UserService,
  	public campaignService: CampaignService,
    public fb: FormBuilder,
    public router: Router,
    public route: ActivatedRoute,){
      this.current_url = this.router.url;
      this.submitFrom = this.fb.group({
        'campaign_name': ['', [Validators.required]],
        'campaign_goal': ['', [Validators.required]],
        'campaign_live_date': ['', [Validators.required]],
        'is_landing_page': [true, [Validators.required]],
        'is_qr_code': [true, [Validators.required]],
        'is_call_tracking_number': [false, [Validators.required]]
      });
      this.default_date = new Date();
      this.currentDate = moment();
      this.minDate = new Date(this.currentDate)
      this.bsConfig = Object.assign({}, { containerClass: 'theme-dark-blue', dateInputFormat: 'MM/DD/YYYY', minDate:this.minDate } );
    }

  ngOnInit(): void {
    this.userService.page_title = "Edit Campaign";
    this.route.data.subscribe((data:any) => {
      let result = data['detail'];
      this.detail = result.data;
      for (var i in this.detail) {
        if (this.detail[i] === 'YES') {
          this.detail[i] = true;
        }
        if (this.detail[i] === 'NO') {
          this.detail[i] = false;
        }
      }
    
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });

    let dd:any = moment(this.detail.campaign_live_date);
    this.default_date = dd._d;
    this.submitFrom = this.fb.group({
      campaign_id: [this.param_id, Validators.required],
      campaign_name: [this.detail.campaign_name, Validators.required],
      campaign_goal: [this.detail.campaign_goal, Validators.required],
      campaign_live_date: [this.detail.campaign_live_date, [Validators.required]],
      is_landing_page: [this.detail.is_landing_page, [Validators.required]],
      is_qr_code: [this.detail.is_qr_code, [Validators.required]],
      is_call_tracking_number: [this.detail.is_call_tracking_number, [Validators.required]]
    });
    setTimeout(() => {
      const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
      this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.detail.campaign_guid + '/edit';
      this.onFeatureChange();
    }, 300);
    });
  }

  onFeatureChange() {
    const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
    this.campaignService.campaign_tabs[cr_index].is_tab = true;
    this.campaignService.campaign_tabs[cr_index].active = false;
    this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.param_id + '/edit';

    this.campaignService.campaign_tabs[0].active = true;
    const is_landing_page = this.submitFrom.get('is_landing_page').value;
    const is_call_tracking_number = this.submitFrom.get('is_call_tracking_number').value;
    const is_qr_code = this.submitFrom.get('is_qr_code').value;
    if (is_landing_page) {
      const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
      this.campaignService.campaign_tabs[lp_index].is_tab = true;
      this.campaignService.campaign_tabs[lp_index].active = false;
      if (is_landing_page && !this.detail.template_values) {
        this.campaignService.campaign_tabs[lp_index].url = '/user/campaigns/' + this.param_id + '/templates';
      } else if (is_landing_page && this.detail.template_values) {
        this.campaignService.campaign_tabs[lp_index].url = '/user/campaigns/' + this.param_id + '/design';
      }
    } else {
      const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
      this.campaignService.campaign_tabs[lp_index].is_tab = false;
      this.campaignService.campaign_tabs[lp_index].active = false;
      this.campaignService.campaign_tabs[lp_index].url = '';
    }
    if (is_call_tracking_number) {
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
    if (is_qr_code) {
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
      this.tabs_css = 1;
      return 1;
    }
    if (tabs_array.length === 2) {
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

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

  onValueChange(value: Date): void {
    if(value){
      let broadcast_sent_date = moment(this.default_date).format('YYYY-MM-DD');
      const campaign_live_date_control = this.submitFrom.get('campaign_live_date').setValue(broadcast_sent_date);
      
    }
  }

  submitForm() {
    let form_values:any = this.submitFrom.value
    let is_selcted = true;
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
      this.campaignService.editCampaign(form_values).subscribe((response: any) => {
        this.isSubmitting = false;
        this.userService.alerts.push({
          type: 'success',
          msg: response.message,
          timeout: 4000
        });
          if (form_values.is_call_tracking_number == 'YES') {
            this.router.navigate(['/user/campaigns/' + this.param_id + '/call-tracking-number']);
          } else if (form_values.is_landing_page == 'YES' && !this.detail.template_values) {
            this.router.navigate(['/user/campaigns/' + this.param_id + '/templates']);
          } else if (form_values.is_landing_page == 'YES' && this.detail.template_values) {
            this.router.navigate(['/user/campaigns/' + this.param_id + '/design']);
          } else if (form_values.is_qr_code == 'YES'){
            this.router.navigate(['/user/campaigns/' + this.param_id + '/qr-code']);
          }else{
            this.router.navigate(['/user/campaigns']);
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

  goToLandingPage() {
    const is_landing_page = this.submitFrom.get('is_landing_page').value;
    if (is_landing_page && !this.detail.template_values) {
      this.router.navigate(['/user/campaigns/' + this.param_id + '/templates']);
    } else if (is_landing_page && this.detail.template_values) {
      this.router.navigate(['/user/campaigns/' + this.param_id + '/design']);
    }
  }

  goToBack(){
      this.location.back();
  }
}
