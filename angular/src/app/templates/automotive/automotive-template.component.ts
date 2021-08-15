import {Component, OnInit, Input, TemplateRef, ElementRef, ViewChild} from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { ContentEditModalComponent } from '../../shared';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { Router, ActivatedRoute } from '@angular/router';
import html2canvas from 'html2canvas';

@Component({
  selector: 'automotive-template',
  templateUrl: './automotive-template.component.html',
  styleUrls: ['./automotive-template.component.css']
})
export class AutomotiveTemplateComponent implements OnInit{
  @ViewChild('screen') screen: ElementRef;
  public modalRef: BsModalRef;
  public config_invite_users = {
    backdrop: true,
    ignoreBackdropClick: true,
    class : 'modal-lg'
  };

  @Input() details: any;
  @Input() mode: any = 'edit';
  public isSubmitting: boolean = false;
  public alerts: any = [];
  public param_id: any;
  public target_value: any;
  public media_object: any = {};
  public image_id: any = 1;
  public config = {
    ignoreBackdropClick: true,
    class: 'modal-md'
  };
  public cropper_config = {
    ignoreBackdropClick: true,
    class: 'modal-lg'
  };
  public tabs_css = 4;
  constructor(
    public userService: UserService,
    public campaignService: CampaignService,
    public modalService: BsModalService,
    public router: Router,
    public route: ActivatedRoute
    ){
      this.route.params.subscribe(params => {
        this.param_id = params.id;
      });
    }

    ngOnInit(): void {
      this.userService.page_title = "Template";
      if (this.details.template_values) {
        let parseValues = JSON.parse(this.details.template_values);
        this.campaignService.automotive_values = parseValues; 
      }else if (this.details.default_values){
        this.campaignService.automotive_values = this.details.default_values;
      }
      this.onFeatureChange();
    }

    onFeatureChange() {
      const cr_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'create');
      this.campaignService.campaign_tabs[cr_index].is_tab = true;
      this.campaignService.campaign_tabs[cr_index].active = false;
      this.campaignService.campaign_tabs[cr_index].url = '/user/campaigns/' + this.param_id + '/edit';

      if (this.details.is_landing_page === 'YES') {
        const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
        this.campaignService.campaign_tabs[lp_index].is_tab = true;
        this.campaignService.campaign_tabs[lp_index].active = true;
        if (this.details.is_landing_page && !this.details.template_values) {
          this.campaignService.campaign_tabs[lp_index].url = '/user/campaigns/' + this.param_id + '/templates';
        } else if (this.details.is_landing_page && this.details.template_values) {
          this.campaignService.campaign_tabs[lp_index].url = '/user/campaigns/' + this.param_id + '/design';
        }
      } else {
        const lp_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'landingpage');
        this.campaignService.campaign_tabs[lp_index].is_tab = false;
        this.campaignService.campaign_tabs[lp_index].active = false;
        this.campaignService.campaign_tabs[lp_index].url = '';
      }
      if (this.details.is_call_tracking_number === 'YES') {
        const ct_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'calltracking');
        this.campaignService.campaign_tabs[ct_index].is_tab = true;
        this.campaignService.campaign_tabs[ct_index].active = false;
        this.campaignService.campaign_tabs[ct_index].url = '/user/campaigns/' + this.param_id + '/call-tracking-number';
      } else {
        const ct_index = this.campaignService.campaign_tabs.findIndex(p => p.value === 'calltracking');
        this.campaignService.campaign_tabs[ct_index].is_tab = false;
        this.campaignService.campaign_tabs[ct_index].active = false;
        this.campaignService.campaign_tabs[ct_index].url = '';
      }
      if (this.details.is_qr_code === 'YES') {
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

  updateTemplateDetails(){
    this.mode = 'view';
    let data = {
      campaign_id: this.param_id,
      template_values : JSON.stringify(this.campaignService.automotive_values)
    }
    this.isSubmitting = true;
    this.campaignService.updateTemplateDetails(data).subscribe((response: any) => {
      let data = response.data;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.pdfDownload();
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

  pdfDownload() {
    this.isSubmitting = true;
    let self = this;//use this variable to access your class members inside then().
    let val:any = this.screen.nativeElement
    html2canvas(val, { logging:false, }).then(canvas => {
        var imgData = canvas.toDataURL("image/png");
        self.uploadimage(imgData);
   });
  }

  uploadimage(image) {
    this.isSubmitting = false;
    if (image) {
      this.isSubmitting = true;
      let stringLength = image.length - '/^data:image\/[a-z]+;base64,/'.length;
      let file_size_in_kb_bytes = 4 * Math.ceil((stringLength / 3)) * 0.5624896334383812;
      let file_size_in_kb = file_size_in_kb_bytes / 1000;
      let cropped_image = image.replace(/^data:image\/[a-z]+;base64,/, "");
  
      let image_data = { 
        type_id: this.param_id,
        image_crop: cropped_image,
        file_size: file_size_in_kb,
        type: 'image',
        original_name: 'base_template.jpg',
        image_type:'preview_tempate'
      }
      this.campaignService.postBaseImage(image_data).subscribe(data => {
        // this.userService.alerts.push({
        //   type: 'success',
        //   msg: data.message,
        //   timeout: 4000
        // });
        this.isSubmitting = false;
        this.router.navigate(['/user/campaigns/',this.param_id,'setting']);
      }), err => {
        this.isSubmitting = false;
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
      }
    }
  }

  getTargetValue(target){
    let target_array = target.split("-");
    let value = '';
    if (target_array.length === 2) {
      value = this.campaignService.automotive_values[target_array[0]][target_array[1]];
    } else if(target_array.length === 3) {
      value = this.campaignService.automotive_values[target_array[0]][target_array[1]][target_array[2]];
    } else if(target_array.length === 4){
      value = this.campaignService.automotive_values[target_array[0]][target_array[1]][target_array[2]][target_array[3]];
    } else if(target_array.length === 5){
      value = this.campaignService.automotive_values[target_array[0]][target_array[1]][target_array[2]][target_array[3]][target_array[4]];
    }

    return value;
  }

  updateText(target){
    let value = '';
    value = this.getTargetValue(target);
    this.target_value = value;
    let initialState:any = {
      value:value
    }
    this.modalRef = this.modalService.show(ContentEditModalComponent, {initialState});
    this.modalRef.content.modalClose.subscribe((result)=>{
      value = result;
    })
  }

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

  openModal(template: TemplateRef<any>) {
    this.modalRef = this.modalService.show(template, this.cropper_config);
  }

  openFileUploadModal(template: TemplateRef<any>, target) {
    this.target_value = target;
    this.modalRef = this.modalService.show(template, this.config);
  }

  receiveFileResponse($event: any) {
    let values:any = '';
    values = this.getTargetValue(this.target_value);
    this.media_object = $event.data;
    values.image_id = this.media_object.media_guid;
    values.image_url = this.media_object.full_path;
    values.alt_tag = "Image";
    values.title = this.media_object.original_name;
    this.hideModalFromChild();
  }

  receiveBannerResponse($event: any) {
    this.media_object = $event.data;
    this.campaignService.automotive_values.banner_background_image = {
      "image_id":this.media_object.media_guid,
      "image_url":this.media_object.full_path,
      "alt_tag":"Image",
      "title":this.media_object.original_name,
    }
    this.hideModalFromChild();
  }

  previewTemplate(type){
    this.mode = type;
  }

  hideModalFromChild() {
    // setTimeout(() => {
    // }, 200);
    
    this.modalRef.hide();
  }
}