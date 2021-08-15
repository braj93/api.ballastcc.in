import {Component, OnInit, ViewChild} from '@angular/core';
import { UserService, AdminService } from '../../core';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { Router } from '@angular/router';
import { DaterangepickerConfig, DaterangepickerComponent } from 'ng2-daterangepicker';
import * as moment from 'moment';
import { BsDaterangepickerDirective, BsDatepickerConfig } from 'ngx-bootstrap/datepicker';
import { Location } from '@angular/common';
@Component({
  selector: 'app-add-broadcast-profile',
  templateUrl: './add-broadcast.component.html',
  styleUrls: ['./add-broadcast.component.css']
})
export class AddBroadcastComponent implements OnInit{

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
	// public daterange: any = {
	//   "start": moment().startOf('month'), 
	//   "end": moment().endOf('month')
	//    };
  public currentDate:any;
  public isSubmitting: boolean = false;
  public formValue: any = {
    'title': '',
    'message': '',
    'is_user_active': false,
    'is_user_inactive': false,
    'is_last_thirty_days_signed_up': false,
    'is_last_login': false,
    // "last_login_from_date": moment(this.daterangedefault.start).format('YYYY-MM-DD'),
    // "last_login_to_date": moment(this.daterangedefault.end).format('YYYY-MM-DD'),
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

  constructor(
    private location: Location,
  	public userService: UserService,
  	public adminService: AdminService,
  	// public daterangepickerOptions: DaterangepickerConfig,
  	// public datepickerOptions: DaterangepickerConfig,
    public fb: FormBuilder,
    public router: Router,

  	){
  	// this.daterangepickerOptions.settings = {
  	//       locale: { format: 'MMM DD, YYYY',
  	//           separator: ' to '
  	//        },
  	//       alwaysShowCalendars: false,
  	//       ranges: {
  	//              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
  	//              'Last 60 Days': [moment().subtract(59, 'days'), moment()],
  	//              'Last 90 Days': [moment().subtract(89, 'days'), moment()],
  	//              'Last 180 Days': [moment().subtract(179, 'days'), moment()],
  	//       },
  	//       skipCSS : true,
  	//       startDate: this.daterangedefault.start, 
  	//       endDate: this.daterangedefault.end
    //   };
      
      this.currentDate = moment();
      
      this.schedule_date = new Date();
  
    // this.maxDate.setDate(this.maxDate.getDate() + 7);
    this.bsRangeValue = [this.bsValue, this.maxDate];
    if (this.bsRangeValue.length > 0) {
      this.formValue.last_login_from_date = moment(this.bsRangeValue[0]).format('YYYY-MM-DD');
      this.formValue.last_login_to_date = moment(this.bsRangeValue[1]).format('YYYY-MM-DD');
    }
    // console.log(this.formValue);
    this.minDate = new Date(this.currentDate)
    this.bsConfig = Object.assign({}, { containerClass: 'theme-dark-blue', dateInputFormat: 'MM/DD/YYYY', minDate:this.minDate } );
    this.bsRangeConfig = Object.assign({}, { containerClass: 'theme-dark-blue', dateInputFormat: 'MM/DD/YYYY' } );
  }

  ngOnInit(): void {
    this.userService.page_title = "Add Broadcast";
    this.getPlans('AGENCY');
    this.getPlans('NON_AGENCY');
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
      // const last_login_from_dateControl = this.bform.get('broadcast_sent_date').setValue(moment(this.schedule_date).format('YYYY-MM-DD'));
      // this.schedule_date = value;
      let broadcast_sent_date = moment(this.schedule_date).format('YYYY-MM-DD');
      const last_login_from_dateControl = this.bform.get('broadcast_sent_date').setValue(moment(this.schedule_date).format('YYYY-MM-DD'));
      this.schedule_date = value;
      let scheduled_at_date = this.bform.get('scheduled_at').value;
      let time = moment(scheduled_at_date).format('HH:mm:00');
      let date_time = broadcast_sent_date + " " + time;
      const scheduled_at_control = this.bform.get('scheduled_at').setValue(date_time);
    }
  }

  onRangeValueChange(value: any){
    if (this.bsRangeValue.length > 0) {
      const last_login_from_dateControl = this.bform.get('last_login_from_date').setValue(moment(this.bsRangeValue[0]).format('YYYY-MM-DD'));
      const last_login_to_dateControl = this.bform.get('last_login_to_date').setValue(moment(this.bsRangeValue[1]).format('YYYY-MM-DD'));
      this.formValue.last_login_from_date = moment(this.bsRangeValue[0]).format('YYYY-MM-DD');
      this.formValue.last_login_to_date = moment(this.bsRangeValue[1]).format('YYYY-MM-DD');
    }
  }

  // selectedDate(value: any) {
  //   const last_login_from_dateControl = this.bform.get('last_login_from_date').setValue(moment(value.start._d).format('YYYY-MM-DD'));
  //   const last_login_to_dateControl = this.bform.get('last_login_to_date').setValue(moment(value.end._d).format('YYYY-MM-DD'));
  //   this.formValue.last_login_from_date = moment(value.start._d).format('YYYY-MM-DD');
  //   this.formValue.last_login_to_date = moment(value.end._d).format('YYYY-MM-DD');
  // }

  checkboxChange(is_checked, id, type){
    let checkValues = [];
    let that = this;
    if(type == 'agency'){
      if(id == 'all'){
        if(is_checked){
          Object.keys(this.formValue.agency).forEach((obj)=>{
            that.formValue.agency[obj] = true;
          }); 
        }else{
          Object.keys(this.formValue.agency).forEach((obj)=>{
            that.formValue.agency[obj] = false;
          });   
        }
      }else {
        let is_all_checked = true;
        Object.keys(this.formValue.agency).forEach((obj)=>{
          if(!that.formValue.agency[obj]){
            is_all_checked = false;
          }
        });
        this.formValue.agency.all = is_all_checked;
      }
      Object.keys(this.formValue.agency).forEach((obj)=>{
        if(that.formValue.agency[obj]){
          checkValues.push(obj);
        }
      });
      const agencyControl = this.bform.get('agency').setValue(checkValues.join());
    }else{
      if(id == 'all'){
        if(is_checked){
          Object.keys(this.formValue.non_agency).forEach((obj)=>{
            that.formValue.non_agency[obj] = true;
          }); 
        }else{
          Object.keys(this.formValue.non_agency).forEach((obj)=>{
            that.formValue.non_agency[obj] = false;
          });   
        }
      }else {
        let is_all_checked = true;
        Object.keys(this.formValue.non_agency).forEach((obj)=>{
          if(!that.formValue.non_agency[obj]){
            is_all_checked = false;
          }
        });
        this.formValue.non_agency.all = is_all_checked;
      }
      Object.keys(this.formValue.non_agency).forEach((obj)=>{
        if(that.formValue.non_agency[obj]){
          checkValues.push(obj);
        }
      });
      const agencyControl = this.bform.get('non_agency').setValue(checkValues.join());
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
    this.isSubmitting = true;
    this.adminService.addBroadcast(cr).subscribe((response: any) => {
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

  getPlans(type){
    this.adminService.getPlans({type: type}).subscribe((response: any)=> {
      let that = this;
      if(type == 'AGENCY') {
        this.agency_plans = response.data;
        this.agency_plans.forEach(function (value) {
          that.agency_plans_label[value.id]=value.name;
          that.formValue.agency[value.id] = that.formValue.agency[value.id] || false;
          that.formValue.agency[value.id] = false;
        });
      } else {
        this.non_agency_plans = response.data;
        this.non_agency_plans.forEach(function (value) {
          that.non_agency_plans_label[value.id]=value.name;
          that.formValue.non_agency[value.id] = that.formValue.non_agency[value.id] || false;
          that.formValue.non_agency[value.id] = false;
        });
      }
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
  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

  goToBack(){
      this.location.back();
  }
  
}