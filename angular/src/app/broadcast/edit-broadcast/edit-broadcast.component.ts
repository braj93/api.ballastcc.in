import {Component, OnInit, ViewChild} from '@angular/core';
import { UserService, AdminService } from '../../core';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { DaterangepickerConfig, DaterangepickerComponent } from 'ng2-daterangepicker';
import * as moment from 'moment';
import { BsDaterangepickerDirective, BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { Location } from '@angular/common';
@Component({
  selector: 'app-edit-broadcast-profile',
  templateUrl: './edit-broadcast.component.html',
  styleUrls: ['./edit-broadcast.component.css']
})
export class EditBroadcastComponent implements OnInit{

  public quillConfig = {
    // toolbar: '.toolbar',
    toolbar: {
      container: [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        // ['code-block'],
        // [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        //[{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        //[{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        //[{ 'direction': 'rtl' }],                         // text direction
        [{'styles': [{'height': '800px'}]}],
        //[{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        //[{ 'font': [] }],
        [{ 'align': [] }],

        // ['clean'],                                         // remove formatting button

        // ['link'],
        //['link', 'image', 'video']  
        // ['emoji'], 
      ],
      // handlers: {'emoji': function() {}}
    },
  }

  @ViewChild(DaterangepickerComponent)
  public required_fields = [];
  private picker: DaterangepickerComponent;
	public agency_plans:any = [];
  public non_agency_plans:any = [];
  public agency_plans_label:any = {all: "All"};
  public non_agency_plans_label:any = {all: "All"};
	public daterangedefault: any = {
	  "start": moment().startOf('month'), 
	  "end": moment()
  };
  public currentDate:any;
  public isSubmitting: boolean = false;
  public formValue: any = {
    'title': '',
    'message': '',
    'is_user_active': false,
    'is_user_inactive': false,
    'is_last_thirty_days_signed_up': false,
    'is_last_login': false,
    "last_login_from_date": '',
    "last_login_to_date": '',
    'is_agnecy': false,
    'agency': { all:false},
    'is_non_agnecy': false,
    'non_agency': { all:false}
  }
  
  bform: FormGroup;
  bsValue = new Date(this.daterangedefault.start);
  bsRangeValue: Date[];
  maxDate = new Date(this.daterangedefault.end);
  public bsConfig: Partial<BsDatepickerConfig>;
  public bsRangeConfig: Partial<BsDatepickerConfig>;
  public minDate:any = '';
  public schedule_date:any = '';
  public detail:any;
  public param_id: any;
  constructor(
    private location: Location,
  	public userService: UserService,
  	public adminService: AdminService,
    public fb: FormBuilder,
    public router: Router,
    public route: ActivatedRoute,

  	){

    this.currentDate = moment();
    
    this.schedule_date = this.currentDate;
  
    this.bsRangeValue = [this.bsValue, this.maxDate];
    if (this.bsRangeValue.length > 0) {
      this.formValue.last_login_from_date = moment(this.bsRangeValue[0]).format('YYYY-MM-DD');
      this.formValue.last_login_to_date = moment(this.bsRangeValue[1]).format('YYYY-MM-DD');
    }
    this.minDate = new Date(this.currentDate)
    this.bsConfig = Object.assign({}, { containerClass: 'theme-dark-blue', dateInputFormat: 'MM/DD/YYYY', minDate:this.minDate } );
    this.bsRangeConfig = Object.assign({}, { containerClass: 'theme-dark-blue', dateInputFormat: 'MM/DD/YYYY' } );
    this.bform = this.fb.group({
      title: ['', Validators.required],
      message: ['', Validators.required],
      broadcast_sent_type: ['NOW', Validators.required],
      broadcast_sent_date: [''],
      broadcast_sent_time: [''],
      scheduled_at: [''],
      time_seconds: [''],
      is_user_active: false,
      is_user_inactive: false,
      is_last_thirty_days_signed_up: false,
      is_last_login: false,
      last_login_from_date: moment(this.daterangedefault.start).format('YYYY-MM-DD'),
      last_login_to_date: moment(this.daterangedefault.end).format('YYYY-MM-DD'),
      is_agnecy: false,
      agency: "",
      is_non_agnecy: false,
      non_agency: "",
      is_agency_users: false,
      is_agency_envitee_users: false,
      is_individual_users: false
    });
  }

  ngOnInit(): void {
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.userService.page_title = "Edit Broadcast";
    this.route.data.subscribe((data:any) => {
      let result = data['broadcast'];
      this.detail = result.data;
      for (var i in this.detail) {
        if (this.detail[i] === 'YES') {
          this.detail[i] = true;
        }
        if (this.detail[i] === 'NO') {
          this.detail[i] = false;
        }
      }
    });


    if(this.detail.is_last_login){
      let last_login_from_date:any = moment(this.detail.last_login_from_date);
      let last_login_to_date:any = moment(this.detail.last_login_to_date);
      this.bsRangeValue = [last_login_from_date._d,  last_login_to_date._d];
      if (this.bsRangeValue.length > 0) {
        this.formValue.last_login_from_date = moment(this.bsRangeValue[0]).format('YYYY-MM-DD');
        this.formValue.last_login_to_date = moment(this.bsRangeValue[1]).format('YYYY-MM-DD');
      }
    }
    if(this.detail.broadcast_sent_type == 'SCHEDULED'){
      let dd:any = moment(this.detail.broadcast_sent_date);
      this.schedule_date = dd._d;
    }else{
      this.schedule_date = this.currentDate._d;
    }
    
    this.bform = this.fb.group({
      title: [this.detail.title, Validators.required],
      message: [this.detail.message, Validators.required],
      broadcast_sent_type: [this.detail.broadcast_sent_type, Validators.required],
      broadcast_sent_date: [this.detail.broadcast_sent_date],
      broadcast_sent_time: [this.detail.broadcast_sent_time],
      scheduled_at: [this.detail.scheduled_at],
      time_seconds: [this.detail.broadcast_sent_time],
      is_user_active: this.detail.is_user_active,
      is_user_inactive: this.detail.is_user_inactive,
      is_last_thirty_days_signed_up: this.detail.is_last_thirty_days_signed_up,
      is_last_login: this.detail.is_last_login,
      last_login_from_date: moment(this.daterangedefault.start).format('YYYY-MM-DD'),
      last_login_to_date: moment(this.daterangedefault.end).format('YYYY-MM-DD'),
      is_agnecy: this.detail.is_agnecy,
      agency: "",
      is_non_agnecy: this.detail.is_non_agnecy,
      non_agency: "",
      is_agency_users: this.detail.is_agency_users,
      is_agency_envitee_users: this.detail.is_agency_envitee_users,
      is_individual_users: this.detail.is_individual_users
    });
  }


  changed(event){
    if(event){
      let broadcast_sent_date = this.bform.get('broadcast_sent_date').value;
      let time = moment(event).format('HH:mm:00');
      let date_time = broadcast_sent_date + " " + time;
      let utc_date_time = moment(date_time).utc().format('YYYY-MM-DD HH:mm:00');
      let seconds = moment(event).diff(moment().startOf('day'), 'seconds');
      const time_seconds_control = this.bform.get('time_seconds').setValue(seconds);
      const scheduled_at_control = this.bform.get('scheduled_at').setValue(utc_date_time);
    }
  }

  scheduleChange(){
    let broadcast_sent_type = this.bform.get('broadcast_sent_type').value;
    let broadcast_sent_date = this.bform.get('broadcast_sent_date');
    let broadcast_sent_time = this.bform.get('broadcast_sent_time');
    if(broadcast_sent_type == 'SCHEDULED'){
      broadcast_sent_time.setValidators([Validators.required]);
      broadcast_sent_time.updateValueAndValidity();
    } else{
      broadcast_sent_time.setValue('');
      broadcast_sent_time.clearValidators();
      broadcast_sent_time.updateValueAndValidity();
    }
  }

  onValueChange(value: Date): void {
    if(value){
    let broadcast_sent_date = moment(this.schedule_date).format('YYYY-MM-DD')
      const last_login_from_dateControl = this.bform.get('broadcast_sent_date').setValue(moment(this.schedule_date).format('YYYY-MM-DD'));
      this.schedule_date = value;
      let scheduled_at_date = this.bform.get('scheduled_at').value;
      let time = moment(scheduled_at_date).format('HH:mm:00');
      let date_time = broadcast_sent_date + " " + time;
      const scheduled_at_control = this.bform.get('scheduled_at').setValue(date_time);
    }
  }

  onRangeValueChange(value: any){
    if (value && value.length > 0) {
      const last_login_from_dateControl = this.bform.get('last_login_from_date').setValue(moment(value[0]).format('YYYY-MM-DD'));
      const last_login_to_dateControl = this.bform.get('last_login_to_date').setValue(moment(value[1]).format('YYYY-MM-DD'));
      this.formValue.last_login_from_date = moment(value[0]).format('YYYY-MM-DD');
      this.formValue.last_login_to_date = moment(value[1]).format('YYYY-MM-DD');
    }
  }

  submitForm() {
    let cr:any = this.bform.value
    let is_selcted = true;
    for (var i in cr) {
      if (cr[i] === true) {
        cr[i] = 'YES';
        is_selcted = false;
      }
      if (cr[i] === false) {
        cr[i] = 'NO';
      }
    }
    if (is_selcted) {
      this.userService.alerts.push({
        type: 'danger',
        msg: 'Please select atleast one type',
        timeout: 4000
      });
    return;
    } else {
      if (cr.broadcast_sent_type == 'NOW') {
        let utc_date_time = moment().utc().format('YYYY-MM-DD HH:mm:00');
        cr.scheduled_at = utc_date_time;
      }
    cr.broadcast_id = this.param_id;
    this.isSubmitting = true;
    // console.log(cr);
    // return;
    this.adminService.updateBroadcast(cr).subscribe((response: any) => {
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.router.navigate(['/console/broadcast']);
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
  }

  arrayOfObject(obj:any){
    return Object.keys(obj);
  }
  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

  goToBack(){
      this.location.back();
  }
  
}