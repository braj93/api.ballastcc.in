import { Output, Input, Component, OnInit, ViewChild, Inject, Renderer2, ElementRef, ChangeDetectionStrategy, HostListener, EventEmitter } from '@angular/core';
import { Router } from '@angular/router';
import { LyTheme2 } from '@alyle/ui';
import { LyResizingCroppingImages, ImgCropperConfig, ImgCropperEvent } from '@alyle/ui/resizing-cropping-images';
import { CampaignService } from '../core';

import { styles } from './app.style';

declare var $: any;

@Component({
  selector: 'app-image-cropper',
  templateUrl: './image-crop.component.html',
  changeDetection: ChangeDetectionStrategy.OnPush,
  styleUrls: ['./image-crop.component.css']
})

export class ImageCropComponent implements OnInit {
  @Input() cover_type: string;
  @Input() cover_type_id: string;
  @Input() cover_media: any;

  cover_response: any;
  @Output() coverEvent = new EventEmitter<any>();
  public screenWidth: number;

  classes = this.theme.addStyleSheet(styles);

  constructor(
    private router: Router,
    private campaignService: CampaignService,
    private theme: LyTheme2
  ) {
    if (this.cover_media === undefined) {
      this.cover_media = {}
    }
    this.getScreenSize();
    //  console.log(this.screenWidth); 
  }

  croppedImg: string;
  scale: number;
  @ViewChild(LyResizingCroppingImages) imgCropper: LyResizingCroppingImages;

  myConfig: ImgCropperConfig = {
    width: window.innerWidth,
    height: 770,
    fill: '#ff2997'
  };


  @HostListener('window:resize', ['$event'])
  getScreenSize(event?) {
    this.screenWidth = window.innerWidth;
    // console.log(this.screenWidth);
  }

  /** on event */
  croppped_image_type;
  croppped_image_name;
  onCrop(e: ImgCropperEvent) {
    this.croppedImg = e.dataURL;
    this.croppped_image_type = e.type;
    this.croppped_image_name = e.name;
    // console.log(e);
    this.uploadimage();
  }

  uploadimage() {
    if (this.croppedImg) {
      let stringLength = this.croppedImg.length - '/^data:image\/[a-z]+;base64,/'.length;
      let file_size_in_kb_bytes = 4 * Math.ceil((stringLength / 3)) * 0.5624896334383812;
      let file_size_in_kb = file_size_in_kb_bytes / 1000;
      let cropped_image = this.croppedImg.replace(/^data:image\/[a-z]+;base64,/, "");

      let image_data ={ 
        image_crop: cropped_image,
        file_size: file_size_in_kb,
        type: 'image',
        image_type: this.croppped_image_type,
        original_name: this.croppped_image_name,
        cover_type: this.cover_type,
        cover_type_id : this.cover_type_id
      }
      // console.log(image_data);return;
      this.campaignService.postBase64Image(image_data).subscribe(data => {
        this.cover_response = data;
        this.cover_media = data.data;
        this.sendCoverResponse();
      }), err => {
        console.log(err.message);
      }
    }
  }

  sendCoverResponse() {
    this.coverEvent.emit(this.cover_response)
  }
  /** manual crop */
  getCroppedImg() {
    const img = this.imgCropper.crop();

    return img.dataURL;
  }

  ngOnInit() {
    // console.log(this.cover_media);
    // console.log(this.cover_type);
    // console.log(this.cover_type_id);
  }

  ngAfterViewInit() { }


}
