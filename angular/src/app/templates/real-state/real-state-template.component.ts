import {Component, OnInit, Input, TemplateRef, ElementRef, ViewChild} from '@angular/core';
import { UserService, CampaignService } from '../../core';
import { ContentEditModalComponent } from '../../shared';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { Router, ActivatedRoute } from '@angular/router';
// import * as html2canvas from "html2canvas";
import html2canvas from 'html2canvas';
declare var $;
// declare let html2canvas: html2canvas;

@Component({
  selector: 'real-state-template',
  templateUrl: './real-state-template.component.html',
  styleUrls: ['./real-state-template.component.css']
})
export class RealStateTemplateComponent implements OnInit{
  @ViewChild('screen') screen: ElementRef;
  @ViewChild('canvas') canvas: ElementRef;
  @ViewChild('downloadLink') downloadLink: ElementRef;


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
    this.userService.page_title = "Template";
    if (this.details.template_values) {
      let parseValues = JSON.parse(this.details.template_values);
      this.campaignService.real_state_values = parseValues; 
    }else if (this.details.default_values){
      this.campaignService.real_state_values = this.details.default_values;
    }
  }

  updateTemplateDetails(){
    this.mode = 'view';
    let data = {
      campaign_id: this.param_id,
      template_values : JSON.stringify(this.campaignService.real_state_values)
    }
    this.campaignService.updateTemplateDetails(data).subscribe((response: any) => {
      let data = response.data;
      this.isSubmitting = false;
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

  previewTemplate(type){
    this.mode = type;
  }


  // downloadImage(){
  //   let vaeee:any = this.screen.nativeElement
  //   html2canvas().then(canvas => {
  //     var img = canvas.toDataURL("image/png");
  //     console.log(img);
  //     // this.canvas.nativeElement.src = canvas.toDataURL();
  //     // this.downloadLink.nativeElement.href = canvas.toDataURL('image/png');
  //     // this.downloadLink.nativeElement.download = 'marble-diagram.png';
  //     // this.downloadLink.nativeElement.click();{ allowCORS : true, allowTaint: true, logging:true, }
  //   });
  // }

  
// myClickFunction(event: any) {
//   html2canvas(event.target)
//     .then((canvas) => {
//       var img = canvas.toDataURL("image/png")
//       console.log(img);
//       window.open(img);
//     })
//     .catch(err => {
//       console.log("error canvas", err);
//     });
//   }

  pdfDownload() {
    let self = this;//use this variable to access your class members inside then().
    let val:any = this.screen.nativeElement
    html2canvas(val).then(canvas => {
        var imgData = canvas.toDataURL("image/png");
        self.uploadimage(imgData);
        // document.body.appendChild(canvas);
   });

  //  html2canvas(document.body, { allowTaint: true }).then(function(canvas) {
  //   var img = canvas.toDataURL("image/png");
  //   var doc = new jsPDF();
  //   doc.addImage(img,'JPEG',0,0);
  //   doc.save('test.pdf');, {
    //   width: 1440,
    //   height: 930
    // }
  // });
}

uploadimage(image) {
  if (image) {
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
      this.router.navigate(['/user/campaigns/',this.param_id,'setting']);
    }), err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    }
  }
}

  // AddImagesResource(query: any) {
  //   // this.imagesService.addCanvasResource(query)
  //   //     .subscribe(response => {
  //   //         this.eventsEmitter.broadcast('Success', 'Changes Saved Succesfully');
  //   //     },
  //   //     error => {
  //   //         this.eventsEmitter.broadcast('Error', 'Error Occured');
  //   //     });
  // }
}