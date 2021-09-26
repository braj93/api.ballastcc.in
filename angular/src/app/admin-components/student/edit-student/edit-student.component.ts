import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { AdminService } from '../../../core';
import { Router,ActivatedRoute } from '@angular/router';
import { formatDate } from 'ngx-bootstrap/chronos';
@Component({
  selector: 'app-edit-student',
  templateUrl: './edit-student.component.html',
  styleUrls: ['./edit-student.component.css']
})
export class EditStudentComponent implements OnInit {
  public isSubmitting: any = false;
  public submitFrom: FormGroup;
  public class_list: any = [];
  public board_list: any = [];
  public subjects_list: any = [];
  public batch_list: any = [];
  public detail:any;
  public param_id:any;
  constructor(private location: Location,
    public router: Router,
    public fb: FormBuilder,
    public adminService: AdminService,
    public route: ActivatedRoute,
    ) { 
      this.submitFrom = this.fb.group({
        'reg_number': ['', [Validators.required]],
        'reg_date': ['', [Validators.required]],
        'first_name': ['', [Validators.required]],
        'last_name': ['', [Validators.required]],
        'father_name': ['', [Validators.required]],
        'mother_name': ['', [Validators.required]],
        'class': ['', [Validators.required]],
        'batch': ['', [Validators.required]],
        'dob': ['', [Validators.required]],
        'board': ['', [Validators.required]],
        'medium': ['HINDI', [Validators.required]],
        'subjects': [[], [Validators.required]],
        'total_fee': ['', [Validators.required]],
        'remain_fee': ['', [Validators.required]],
        'email': ['', [Validators.email]],
        'school': ['', [Validators.required]],
        'mobile': ['', [Validators.minLength(10)]],
        'alt_mobile': ['', [Validators.minLength(10)]],
        'address': ['', [Validators.required]],
        'status': ['ACTIVE', [Validators.required]],
        
      });
    }

  ngOnInit(): void {
    this.adminService.page_title = 'Add Student';
    this.route.params.subscribe(params => {
      this.param_id = params.id;
      console.log(this.param_id);
    });
    const student_id = {
      "student_id":this.param_id
    }
    this.adminService.getStudentDetailById(student_id).subscribe((response: any) => {
      const data = response.data;
      console.log(data);
      this.submitFrom = this.fb.group({
        'reg_number': [data.reg_number, [Validators.required]],
        'reg_date': [data.reg_date, [Validators.required]],
        'first_name': [data.first_name, [Validators.required]],
        'last_name': [data.last_name, [Validators.required]],
        'father_name': [data.father_name, [Validators.required]],
        'mother_name': [data.mother_name, [Validators.required]],
        'class': [data.class_guid, [Validators.required]],
        'batch': [data.batch_guid, [Validators.required]],
        'dob': [data.dob, [Validators.required]],
        'board': [data.board_guid, [Validators.required]],
        'medium': [data.medium, [Validators.required]],
        'subjects': [data.subjects, [Validators.required]],
        'total_fee': [data.total_fee, [Validators.required]],
        'remain_fee': [data.remain_fee, [Validators.required]],
        'email': [data.email, [Validators.email]],
        'school': [data.school, [Validators.required]],
        'mobile': [data.mobile, [Validators.minLength(10)]],
        'alt_mobile': [data.alt_mobile, [Validators.minLength(10)]],
        'address': [data.address, [Validators.required]],
        'status': [data.status, [Validators.required]],
      });
      
    },
    err => {
      this.adminService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
      this.isSubmitting = false;
    });

    this.getClassList();
    this.getBatchList();
    this.getBoardList();
    this.getSubjectsList();
  }
  submitForm() {
    const form_values: any = this.submitFrom.value;
    
      this.isSubmitting = true;
      this.adminService.registerStudent(form_values).subscribe((response: any) => {
        const data = response.data;
        console.log(data);
        this.isSubmitting = false;
        // this.adminService.alerts.push({
        //   type: 'success',
        //   msg: response.message,
        //   timeout: 4000
        // });
      },
      err => {
        this.adminService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
        this.isSubmitting = false;
      });
    
  }
  get getControl(){
    return this.submitFrom.controls;
  }
  getClassList(){
    this.adminService.getClassesList().subscribe((response: any) => {
      this.class_list = response.data;
      console.log(this.class_list);
    }, err => {
      console.log(err.message);
    });
  }
  getSubjectsList(){
    this.adminService.getSubjectsList().subscribe((response: any) => {
      this.subjects_list = response.data;
      // console.log(this.subjects_list);
    }, err => {
      console.log(err.message);
    });
  }
  getBatchList(){
    this.adminService.getBatchesList().subscribe((response: any) => {
      this.batch_list = response.data;
      // console.log(this.batch_list);
    }, err => {
      console.log(err.message);
    });
  }
  getBoardList(){
    this.adminService.getBoardsList().subscribe((response: any) => {
      this.board_list = response.data;
      // console.log(this.board_list);
    }, err => {
      console.log(err.message);
    });
  }

  goToBack(){
    this.location.back();
}
ngOnDestroy(): void{
  this.adminService.alerts = [];
}
}
