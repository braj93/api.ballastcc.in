import { Output, Input, Component, OnInit, ViewChild, Inject, Renderer2, EventEmitter, TemplateRef } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { FormBuilder, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Subject } from 'rxjs/Subject'
import { UserService, CrmService, ApiService } from '../../core';
@Component({
  selector: 'app-crm-admin-bulk-contact-modal',
  templateUrl: './crm-admin-bulk-contact-modal.component.html',
  styleUrls: ['./crm-admin-bulk-contact-modal.component.css']
})

export class CrmAdminBulkContactModalComponent implements OnInit {
  public selectedFile: File;
  public isFileSubmitting: boolean = false;
  public uploadedFiledata: any;
  public contact_file: any = '';
  public song_name : any;
  public artist_name : any;
  public emails = [];
  public alerts: any = [];
  public isSubmitting = false;
  public modalForm: FormGroup;
  
  public onClose: Subject<boolean>;
  public modalRef: BsModalRef;
  public config = {
    backdrop: true,
    ignoreBackdropClick: false
  };
  public states: any = [];
  public is_admin: boolean = false;
  public user_list: any = [];
  constructor(
    public userService: UserService,
    public apiService: ApiService,
    public crmService: CrmService,
    private fb: FormBuilder,
    private router: Router,
    public route: ActivatedRoute,
    private modalService: BsModalService,
    public _bsModalRef: BsModalRef
  ) {
    this.modalForm = this.fb.group({
      'user_id': ['', [Validators.required]],
      'contact_file': ['', [Validators.required]]
    });

  }

  ngOnInit() {
    this.getUserIndividual();
  }

  getUserIndividual(){
    this.crmService.getUserIndividual({}).subscribe((response: any) => {
      this.user_list = response.data;
    }, err => {
      console.log(err.message);
    });
  }

  onFileChanged(event) {
    console.log("this.modalForm.value", this.modalForm.value);
    // return;
    
    if(!this.modalForm.value.user_id){
      this.alerts.push({
        type: 'danger',
        msg: 'Please select user',
        timeout: 2000
      });
      let contact_file_control = this.modalForm.get('contact_file');
      contact_file_control.setValue('');
      return;
    }
    this.selectedFile = event.target.files[0];
    if (this.selectedFile) {
      this.isFileSubmitting = true;
      this.apiService.postBulkMultiPart(this.selectedFile, 'excel', this.modalForm.value.user_id).subscribe(data => {
        this.uploadedFiledata = data.data;
        let contact_file_control = this.modalForm.get('contact_file');
        contact_file_control.setValue('');
        this.isFileSubmitting = false;
        this.alerts.push({
          type: 'success',
          msg: data.message,
          timeout:  4000
        });
        setTimeout(() => {
            this.isSubmitting = false;
            this.alerts = [];
        }, 2000);
        
        this.hideModal();
      }, err => {
        let contact_file_control = this.modalForm.get('contact_file');
        contact_file_control.setValue('');
        this.isFileSubmitting = false;
        this.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
      });
    }
  }

  submitForm(){
    // console.log("this.modalForm.value", this.modalForm.value);
    this.isSubmitting = true;
    this.crmService.addCrm(this.modalForm.value).subscribe((response: any) => {
      this.hideModal();
      this.alerts.push({
        type: 'success',
        msg: 'Added Successfully!',
        timeout: 4000
      });
      setTimeout(() => {
          this.isSubmitting = false;
          this.alerts = [];
      }, 2000);
    }, err => {
      this.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      setTimeout(() => {
        this.isSubmitting = false;
        this.userService.alerts = [];
    }, 2000);
      setTimeout(() => {
          this.isSubmitting = false;
      }, 2000);
    });
  }

  hideModal() {
    this._bsModalRef.hide();
  }

  
  ngOnDestroy(): void{
    this.alerts = [];
  }
}