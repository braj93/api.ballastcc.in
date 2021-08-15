import { Output, Input, Component, OnInit, OnDestroy } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { FormBuilder, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Subject } from 'rxjs/Subject'
import { UserService, CrmService } from '../../core';
import {BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import * as moment from 'moment';
@Component({
  selector: 'app-crm-add-contact-modal',
  templateUrl: './crm-add-contact-modal.component.html',
  styleUrls: ['./crm-add-contact-modal.component.css']
})

export class CrmAddContactModalComponent implements OnInit, OnDestroy {
  // public bsConfig: Partial<BsDatepickerConfig>;
  // public bsRangeConfig: Partial<BsDatepickerConfig>;
  // public minDate: any = '';
  public tags_list: any = [];
  public years = [];
  public months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  public currentDate: any;
  public default_date: any;
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
  public is_admin = false;
  public user_list: any = [];
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
      'user_id': ['', [Validators.required]],
      'source_type': ['', [Validators.required]],
      'source_type_other': [''],
      'birthday_month': [''],
      'birthday_year': [''],
      'more_info': ['']
    });

    // this.default_date = '';
    // // this.default_date = new Date();
    //   this.currentDate = moment();
    //   this.bsConfig = Object.assign({}, { containerClass: 'theme-dark-blue', dateInputFormat: 'MM/YYYY', minDate: this.minDate } );

    // this.userService.currentUser.subscribe(
    //   (userData) => {
    //   this.currentUser = userData;
    // });

    const year = new Date().getFullYear();
    this.years.push(year);
    for (let i = 1; i < 50; i++) {
      this.years.push(year - i);
    }
  }

  // onValueChange(value: Date): void {
  //   console.log(value);
  //   if (value) {
  //     const broadcast_sent_date = moment(value).format('YYYY-MM-DD');
  //     const birthday_date_control = this.modalForm.get('birthday_date').setValue(broadcast_sent_date);
  //   }
  // }

  ngOnInit() {
    if (this.crmService.user_type === 'ADMIN') {
      this.getUserIndividual();
      this.is_admin = true;
    }
    this.getStates();

    if (!this.is_admin) {
      const user_id_control = this.modalForm.get('user_id');
      user_id_control.setValue(this.userService.param_id);
      this.getSourceList(this.currentUser.user_guid);
    }
  }


  getStates() {
    this.userService.getStatesList().subscribe((response: any) => {
      this.states = response.data;
    }, err => {
      console.log(err.message);
    });
  }

  getUserIndividual() {
    this.crmService.getUserIndividual({}).subscribe((response: any) => {
      this.user_list = response.data;
    }, err => {
      console.log(err.message);
    });
  }

  submitForm() {
    this.isSubmitting = true;
    this.crmService.addCrm(this.modalForm.value).subscribe((response: any) => {
      this.alerts.push({
        type: 'success',
        msg: 'Added Successfully!',
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
      setTimeout(() => {
        this.isSubmitting = false;
        this.alerts = [];
    }, 2000);
      setTimeout(() => {
          this.isSubmitting = false;
      }, 2000);
    });
  }

  hideModal() {
    this._bsModalRef.hide();
  }

  getSourceList(user_id) {
    this.crmService.getSourceList({user_id: user_id}).subscribe((response: any) => {
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

  userChange() {
    const user_id = this.modalForm.get('user_id').value;
    this.getSourceList(user_id)
  }

  ngOnDestroy(): void {
    this.alerts = [];
  }
}
