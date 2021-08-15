import { Output, Input, Component, OnInit, ViewChild, Inject, Renderer2, EventEmitter, TemplateRef } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { FormBuilder, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Subject } from 'rxjs/Subject'
import { UserService, CrmService } from '../../core';
@Component({
  selector: 'app-crm-edit-contact-modal',
  templateUrl: './crm-edit-contact-modal.component.html',
  styleUrls: ['./crm-edit-contact-modal.component.css']
})

export class CrmEditContactModalComponent implements OnInit {
  public form_details: any;
  public emails = [];
  public alerts: any = [];
  public isSubmitting = false;
  public modalForm: FormGroup;
  public years = [];
  public months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  public onClose: Subject<boolean>;
  public modalRef: BsModalRef;
  public config = {
    backdrop: true,
    ignoreBackdropClick: false
  };
  public states: any = [];
  public currentUser: any = '';
  public show_other = false;
  public source_types = [];
  constructor(
    public userService: UserService,
    public crmService: CrmService,
    private fb: FormBuilder,
    private router: Router,
    public route: ActivatedRoute,
    private modalService: BsModalService,
    public _bsModalRef: BsModalRef
  ) {
    this.modalForm = this.fb.group({
      'crm_contact_name': ['', [Validators.required]],
      'crm_contact_email': ['', [Validators.required, Validators.email]],
      'crm_contact_phone': [''],
      'crm_contact_street': [''],
      'crm_contact_city': [''],
      'crm_contact_state': [''],
      'crm_contact_zipcode': [''],
      'crm_contact_id': [this.crmService.param_id, [Validators.required]],
      'source_type': ['', [Validators.required]],
      'source_type_other': [''],
      'birthday_month': [''],
      'birthday_year': [''],
      'more_info': ['']
    });

    this.userService.currentUser.subscribe(
      (userData) => {
      this.currentUser = userData;
    });
    const year = new Date().getFullYear();
    this.years.push(year);
    for (let i = 1; i < 50; i++) {
      this.years.push(year - i);
    }
  }


  ngOnInit() {
    this.crmService.getDetailsById({'crm_contact_id': this.crmService.param_id}).subscribe((response: any) => {
      this.form_details = response.data;
      this.getSourceList();
      this.setForm();
    }, err => {
      this.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
    this.getStates();
  }

  getStates() {
      this.userService.getStatesList().subscribe((response: any) => {
        this.states = response.data;
      }, err => {
        console.log(err.message);
      });
    }

  setForm() {
    this.modalForm = this.fb.group({
      'crm_contact_name': [this.form_details.crm_contact_name, [Validators.required]],
      'crm_contact_email': [this.form_details.crm_contact_email, [Validators.required, Validators.email]],
      'crm_contact_phone': [this.form_details.crm_contact_phone],
      'crm_contact_street': [this.form_details.crm_contact_street],
      'crm_contact_city': [this.form_details.crm_contact_city],
      'crm_contact_state': [this.form_details.crm_contact_state_id],
      'crm_contact_zipcode': [this.form_details.crm_contact_zipcode],
      'crm_contact_id': [this.crmService.param_id, [Validators.required]],
      'source_type': [this.form_details.source_type],
      'source_type_other': [''],
      'birthday_month': [this.form_details.birthday_month],
      'birthday_year': [this.form_details.birthday_year],
      'more_info': [this.form_details.more_info]
    });
  }

  submitForm() {
    this.isSubmitting = true;
    this.crmService.editCrmContact(this.modalForm.value).subscribe((response: any) => {
      this.alerts.push({
        type: 'success',
        msg: 'Updated Successfully!',
        timeout: 4000
      });
      setTimeout(() => {
          this.isSubmitting = false;
          this.alerts = [];
      }, 2000);
      this.hideModal();
    }, err => {
      this.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      // this.hideModal();
      setTimeout(() => {
          this.isSubmitting = false;
      }, 2000);
    });
  }

  hideModal() {
    this._bsModalRef.hide();
  }

  getSourceList() {
    this.crmService.getSourceList({user_id: this.form_details.user_guid}).subscribe((response: any) => {
      this.source_types = response.data;
    },
      err => {
        this.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
        this.isSubmitting = false;
      });
  }

  sourceTypeChange() {
    const source_type = this.modalForm.get('source_type').value;
    const source_type_other_control = this.modalForm.get('source_type_other');
    if (source_type.toLowerCase() === 'other') {
      this.show_other = true;
      source_type_other_control.setValidators([Validators.required]);
      source_type_other_control.updateValueAndValidity();
    } else {
      this.show_other = false;
      source_type_other_control.setValue('');
      source_type_other_control.clearValidators();
      source_type_other_control.updateValueAndValidity();
    }
  }
}
