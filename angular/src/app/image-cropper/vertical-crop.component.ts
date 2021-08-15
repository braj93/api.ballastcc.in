import { Output, Input, Component, OnInit, ViewChild, ChangeDetectionStrategy, HostListener, EventEmitter } from '@angular/core';
import { LyTheme2 } from '@alyle/ui';
import { LyResizingCroppingImages, ImgCropperConfig, ImgCropperEvent } from '@alyle/ui/resizing-cropping-images';

import { CampaignService } from '../core';

import { styles } from './app-vertical.style';

@Component({
  selector: 'app-vertical-cropper',
  templateUrl: './vertical-crop.component.html',
  changeDetection: ChangeDetectionStrategy.OnPush,
  styleUrls: ['./vertical-crop.component.css']
})

export class VerticalCropComponent implements OnInit {
  @Input() cover_type: string;
  @Input() cover_type_id: string;
  @Input() media_object: any;

  @Output()
  hideClick: EventEmitter<String> = new EventEmitter<String>(); // creating an output event
  cover_response: any;
  @Output() coverEvent = new EventEmitter<any>();
  public screenWidth: number;

  classes = this.theme.addStyleSheet(styles);

  croppped_image_type: any;
  croppped_image_name: any;

  croppedImg: string;
  scale: number;
  @ViewChild(LyResizingCroppingImages) imgCropper: LyResizingCroppingImages;
  myConfig: ImgCropperConfig = {
    width: 300,
    height: 450,
    fill: '#f9f9f9'
  };

  constructor(
    private campaignService: CampaignService,
    private theme: LyTheme2
  ) {
    if (this.media_object === undefined) {
      this.media_object = {}
    }
    this.getScreenSize();
    //  console.log(this.screenWidth);
  }

  hidePopup(event: any) {
    this.hideClick.emit(event); // emmiting the event.
  }

  @HostListener('window:resize', ['$event'])
  getScreenSize(event?) {
    this.screenWidth = window.innerWidth;
    // console.log(this.screenWidth);
  }

  /** on event */
  onCrop(e: ImgCropperEvent) {
    this.croppedImg = e.dataURL;
    this.croppped_image_type = e.type;
    this.croppped_image_name = e.name;
    // console.log(e);
    this.uploadimage();
  }

  uploadimage() {
    if (this.croppedImg) {
      const stringLength = this.croppedImg.length - '/^data:image\/[a-z]+;base64,/'.length;
      const file_size_in_kb_bytes = 4 * Math.ceil((stringLength / 3)) * 0.5624896334383812;
      const file_size_in_kb = file_size_in_kb_bytes / 1000;
      const cropped_image = this.croppedImg.replace(/^data:image\/[a-z]+;base64,/, '');

      const image_data = {
        image_crop: cropped_image,
        file_size: file_size_in_kb,
        type: 'image',
        image_type: this.croppped_image_type,
        original_name: this.croppped_image_name,
        cover_type: this.cover_type,
        cover_type_id: this.cover_type_id
      }
      // console.log(image_data);return;
      this.campaignService.postBase64Image(image_data).subscribe(data => {
        this.cover_response = data;
        this.media_object = data.data;
        this.sendResponse();
      }, (err) => {
        console.log(err.message);
      })
    }
  }

  sendResponse() {
    this.coverEvent.emit(this.cover_response)
  }
  /** manual crop */
  getCroppedImg() {
    const img = this.imgCropper.crop();

    return img.dataURL;
  }

  ngOnInit() { }


}
