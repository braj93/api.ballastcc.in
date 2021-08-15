import {Component, OnInit, Input, TemplateRef} from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { ContentEditModalComponent } from '../../shared';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { Router, ActivatedRoute } from '@angular/router';
declare var $;

@Component({
  selector: 'retail-template',
  templateUrl: './retail-template.component.html',
  styleUrls: ['./retail-template.component.css']
})
export class RetailTemplateComponent implements OnInit{
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
    console.log('sdfdsfdsf');
    this.userService.page_title = "Template";
    if (this.details.template_values) {
      let parseValues = JSON.parse(this.details.template_values);
      this.campaignService.real_state_values = parseValues; 
    }
  }

  updateTemplateDetails(){
    console.log();
    // let data = {
    //   campaign_id: this.param_id,
    //   template_values : JSON.stringify(this.campaignService.real_state_values)
    // }
    // this.campaignService.updateTemplateDetails(data).subscribe((response: any) => {
    //   let data = response.data;
    //   this.isSubmitting = false;
    //   this.userService.alerts.push({
    //     type: 'success',
    //     msg: response.message,
    //     timeout: 4000
    //   });
    //   this.router.navigate(['/user/campaigns/']);
    //   },
    //   err => {
    //     this.userService.alerts.push({
    //       type: 'danger',
    //       msg: err.message,
    //       timeout: 4000
    //     });
    //     this.isSubmitting = false;
    // });
  }

  getTargetValue(target){
    let target_array = target.split("-");
    let value = '';
    if (target_array.length === 2) {
      value = this.campaignService.real_state_values[target_array[0]][target_array[1]];
    } else if(target_array.length === 3) {
      value = this.campaignService.real_state_values[target_array[0]][target_array[1]][target_array[2]];
    } else if(target_array.length === 4){
      value = this.campaignService.real_state_values[target_array[0]][target_array[1]][target_array[2]][target_array[3]];
    } else if(target_array.length === 5){
      value = this.campaignService.real_state_values[target_array[0]][target_array[1]][target_array[2]][target_array[3]][target_array[4]];
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
    this.campaignService.real_state_values.banner_background_image = {
      "image_id":this.media_object.media_guid,
      "image_url":this.media_object.full_path,
      "alt_tag":"Image",
      "title":this.media_object.original_name,
    }
    this.hideModalFromChild();
  }

  hideModalFromChild() {
    // setTimeout(() => {
    // }, 200);
    
    this.modalRef.hide();
  }
}