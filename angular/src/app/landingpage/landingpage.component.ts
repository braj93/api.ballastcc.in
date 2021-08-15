import {Component, OnInit, TemplateRef} from '@angular/core';
import { UserService, CampaignService } from '../core';
import { ActivatedRoute, Router } from '@angular/router';
import { Location } from '@angular/common';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';

declare var $;

@Component({
  selector: 'app-landingpage',
  templateUrl: './landingpage.component.html',
  styleUrls: ['./landingpage.component.css']
})
export class LandingpageComponent implements OnInit{
  public isSubmitting: boolean = false;
  public message: any = '';
  public template_id: any = '';
  public modalRef: BsModalRef;
  public alerts: any = [];
  public rows: any = [];
  public loading: boolean = false;
  public counts: any;
  public page_no: any = 0;
  public parameters:any = {
    keyword: "",
    pagination: {
        offset: 0,
        limit: 6
      },
    sort_by:{
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
  public detail: any = {};
  public param_id: any;
  public tabs_css = 4;
  constructor(
    public modalService: BsModalService,
    public userService: UserService,
    public campaignService: CampaignService,
    public router: Router,
    public route: ActivatedRoute,
    public location: Location,
    ){}

  ngOnInit(): void {
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.userService.page_title = "Landing Page Templates";
    this.getTemplatesList();
    this.route.data.subscribe((data:any) => {
      let result = data['detail'];
      this.detail = result.data;
    });
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

  getTemplatesList(){
    this.campaignService.getTemplateList(this.parameters).subscribe((response: any) => {
      this.isSubmitting = true; 
      this.rows = response.data;
      let templates = response.data;
      templates.forEach((element) => {
        if (!element.default_values && element.template_unique_name != 'template_real_estate_two') {
          this.campaignService.update_template_default_values(element.template_guid, element.template_unique_name).subscribe((response: any) => {
            // console.log(response);
          }, err => {
            // console.log(err);
          });
        }
      });
      this.counts = response.counts;
      this.isSubmitting = false;
      //FOR PAGINATION
      let number: any = Math.ceil(this.counts / this.parameters.pagination.limit);
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

  paginationAction(action){
    this.current_page = action == 'next' ? this.current_page + 1 : this.current_page - 1; 
    this.parameters.pagination.offset = (this.current_page - 1) * this.parameters.pagination.limit;
    this.campaignService.getTemplateList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      let templates = response.data;
      templates.forEach((element) => {
        if (!element.default_values && element.template_unique_name != 'template_real_estate_two') {
          this.campaignService.update_template_default_values(element.template_guid, element.template_unique_name).subscribe((response: any) => {
            // console.log(response);
          }, err => {
            // console.log(err);
          });
        }
      });
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  setPage(page_no) {
    this.current_page = page_no;
    this.parameters.pagination.offset = (page_no - 1) * this.parameters.pagination.limit;
    this.campaignService.getTemplateList(this.parameters).subscribe((response: any) => {
      this.rows = response.data;
      let templates = response.data;
      templates.forEach((element) => {
        if (!element.default_values && element.template_unique_name != 'template_real_estate_two') {
          this.campaignService.update_template_default_values(element.template_guid, element.template_unique_name).subscribe((response: any) => {
            // console.log(response);
          }, err => {
            // console.log(err);
          });
        }
      });
      this.counts = response.counts;
    }, err => {
      console.log(err.message);
    });
  }

  onSort(event){

  }

  addCampaignTemplate(template_id){
    this.campaignService.addCampaignTemplate({campaign_id: this.detail.campaign_guid,template_id:template_id}).subscribe((response: any) => {
      let data = response.data;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.router.navigate(['/user/campaigns', data.campaign_id,'design']);
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.isSubmitting = false;
    });
  }

  updateCampaignTemplate(template_id){
    this.campaignService.updateCampaignTemplate({campaign_id: this.detail.campaign_guid,template_id:template_id}).subscribe((response: any) => {
      let data = response.data;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.router.navigate(['/user/campaigns', data.campaign_id, 'design']);
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.isSubmitting = false;
    });
  }

  openModal(template: TemplateRef<any>, template_id) {
    this.message = 'When you change the template, you will lose your updates to the existing landing page design';
    this.template_id = template_id;
    this.modalRef = this.modalService.show(template, { class: 'modal-md' });
  }

  confirm(): void {
    if (this.detail.campaign_template_guid) {
      // console.log('UPDATE');
      this.updateCampaignTemplate(this.template_id);
    } else {
      // console.log('ADD');
      this.addCampaignTemplate(this.template_id);
    }
    this.modalRef.hide();
  }

  decline(): void {
    this.template_id = '';
    this.message = 'Declined!';
    this.modalRef.hide();
  }

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }
  goToBack() {
    this.location.back();
  }
}
