import { Component, OnInit } from '@angular/core';
import { UserService, AdminService, ApiService } from '../../core';
import { Location } from '@angular/common';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';
declare var $;

@Component({
  selector: 'app-knowledgebase-edit',
  templateUrl: './knowledgebase-edit.component.html',
  styleUrls: ['./knowledgebase.component.css']
})
export class KnowledgebaseEditComponent implements OnInit {
  public kbForm: FormGroup;
  public isSubmitting = false;
  public categories_list: any = [];
  public pricing_plan_list: any = [];
  public selectedFile: File;
  public isFileSubmitting = false;
  public uploadedFiledata: any;
  public param_id: any;
  public form_details: any;
  public tiny_key: any = 'bkoahltwpjq2vulbofjd21wtkpeiofo54h7ip15zcfzd9vm1';
  public quillConfig = {
    // toolbar: '.toolbar',
    toolbar: {
      container: [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        // ['code-block'],
        // [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        // [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        // [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        // [{ 'direction': 'rtl' }],                         // text direction

        // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        // [{ 'font': [] }],
        [{ 'align': [] }],

        // ['clean'],                                         // remove formatting button

        // ['link'],
        // ['link', 'image', 'video']
        // ['emoji'],
      ],
      // handlers: {'emoji': function() {}}
    },
    // autoLink: true,
    // mention: {
    //   allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
    //   mentionDenotationChars: ["@", "#"],
    //   source: (searchTerm, renderList, mentionChar) => {
    //     let values;

    //     if (mentionChar === "@") {
    //       values = this.atValues;
    //     } else {
    //       values = this.hashValues;
    //     }

    //     if (searchTerm.length === 0) {
    //       renderList(values, searchTerm);
    //     } else {
    //       const matches = [];
    //       for (var i = 0; i < values.length; i++)
    //         if (~values[i].value.toLowerCase().indexOf(searchTerm.toLowerCase())) matches.push(values[i]);
    //       renderList(matches, searchTerm);
    //     }
    //   },
    // },
    // "emoji-toolbar": true,
    // "emoji-textarea": false,
    // "emoji-shortname": true,
    // keyboard: {
    //   bindings: {
    //     // shiftEnter: {
    //     //   key: 13,
    //     //   shiftKey: true,
    //     //   handler: (range, context) => {
    //     //     // Handle shift+enter
    //     //     console.log("shift+enter")
    //     //   }
    //     // },
    //     enter:{
    //       key:13,
    //       handler: (range, context)=>{
    //         console.log("enter");
    //         return true;
    //       }
    //     }
    //   }
    // }
  }
  constructor(
    public userService: UserService,
    public adminService: AdminService,
    public apiService: ApiService,
    public location: Location,
    public fb: FormBuilder,
    public router: Router,
    public route: ActivatedRoute,
  ) {
    this.kbForm = this.fb.group({
      'name': ['', [Validators.required]],
      // 'category_id': ['', [Validators.required]],
      'categories': [[], [Validators.required]],
      'pricing_plans': [[], [Validators.required]],
      // 'status': ['ACTIVE', [Validators.required]],
      'is_upload': ['video_embed', [Validators.required]],
      'video_embed_url': ['', [Validators.required]],
      'knowledgebase_media_id': [''],
      'knowledgebase_media_guid': [''],
      'description': ['', [Validators.required]],
    });
  }

  ngOnInit(): void {
    this.userService.page_title = 'Edit Knowledge Base';
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    // this.getKnowledgebaseById(this.param_id);
    this.categoriesList();
    this.getPlans();
    this.route.data.subscribe((data: any) => {
      const result = data['get_by_id'];
      this.form_details = result.data;
    });

    const is_upload = this.form_details.file_name === '' ? 'video_embed' : 'upload_file';

    this.kbForm = this.fb.group({
      'name': [this.form_details.name, [Validators.required]],
      // 'category_id': [this.form_details.category_id, [Validators.required]],
      'categories': [this.form_details.categories, [Validators.required]],
      'pricing_plans': [this.form_details.pricing_plans, [Validators.required]],
      // 'status': [this.form_details.status, [Validators.required]],
      'is_upload': [is_upload, [Validators.required]],
      'video_embed_url': [this.form_details.video_embed_url],
      'knowledgebase_media_id': [this.form_details.media_guid],
      'knowledgebase_media_guid': [''],
      'description': [this.form_details.description, [Validators.required]],
    });

    if (is_upload === 'video_embed') {
      const video_embed_url_control = this.kbForm.get('video_embed_url');
      video_embed_url_control.setValidators([Validators.required]);
      video_embed_url_control.updateValueAndValidity();
    }
  }

  getPlans() {
    this.adminService.getMasterPlansByType({ type: 'NON_AGENCY' }).subscribe((response: any) => {
      this.pricing_plan_list = response.data;
      console.log(this.pricing_plan_list);
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  categoriesList() {
    this.adminService.categoriesList().subscribe((response: any) => {
      this.categories_list = response.data;
      console.log(this.categories_list);
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }

  back() {
    this.location.back();
  }

  isUpload() {
    const video_embed_url_control = this.kbForm.get('video_embed_url');
    const knowledgebase_media_id_control = this.kbForm.get('knowledgebase_media_id');
    const knowledgebase_media_guid_control = this.kbForm.get('knowledgebase_media_guid');
    const is_upload = this.kbForm.get('is_upload');
    if (is_upload.value === 'video_embed') {
      video_embed_url_control.setValidators([Validators.required]);
      video_embed_url_control.updateValueAndValidity();
    } else {
      video_embed_url_control.setValue('');
      video_embed_url_control.clearValidators();
      video_embed_url_control.updateValueAndValidity();
    }

    if (is_upload.value === 'upload_file') {
      knowledgebase_media_id_control.setValidators([Validators.required]);
      knowledgebase_media_id_control.updateValueAndValidity();
      knowledgebase_media_guid_control.setValidators([Validators.required]);
      knowledgebase_media_guid_control.updateValueAndValidity();
    } else {
      knowledgebase_media_id_control.setValue('');
      knowledgebase_media_id_control.clearValidators();
      knowledgebase_media_id_control.updateValueAndValidity();
      knowledgebase_media_guid_control.setValue('');
      knowledgebase_media_guid_control.clearValidators();
      knowledgebase_media_guid_control.updateValueAndValidity();
    }
  }

  onFileChanged(event) {
    this.selectedFile = event.target.files[0];
    if (this.selectedFile) {
      this.isFileSubmitting = true;
      this.apiService.postMultiPart(this.selectedFile, 'image').subscribe(data => {
        this.uploadedFiledata = data.data;
        this.form_details.file_name = data.name;
        // this.knowledgebase_media_id = this.uploadedFiledata.media_guid;
        const knowledgebase_media_id_control = this.kbForm.get('knowledgebase_media_id');
        knowledgebase_media_id_control.setValue(this.uploadedFiledata.media_guid);
        this.isFileSubmitting = false;
        this.userService.alerts.push({
          type: 'success',
          msg: data.message,
          timeout: 4000
        });
      }, err => {
        this.uploadedFiledata = {};
        const knowledgebase_media_id_control = this.kbForm.get('knowledgebase_media_id');
        knowledgebase_media_id_control.setValue('');
        const knowledgebase_media_guid_control = this.kbForm.get('knowledgebase_media_guid');
        knowledgebase_media_guid_control.setValue('');
        knowledgebase_media_guid_control.setValidators([Validators.required]);
        knowledgebase_media_guid_control.updateValueAndValidity();
        this.isFileSubmitting = false;
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
      });
    }
  }

  submitForm() {
    this.isSubmitting = true;
    this.kbForm.value.knowledgebase_id = this.param_id;
    this.adminService.updateKnowledgebase(this.kbForm.value).subscribe((response: any) => {
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.router.navigate(['/console/knowledgebase']);
    },
      err => {
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
        this.isSubmitting = false;
      }
    );
  }

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }
}
