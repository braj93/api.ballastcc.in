import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { AdminService } from '../../core';
import { Router } from '@angular/router';
@Component({
  selector: 'app-add-student',
  templateUrl: './add-student.component.html',
  styleUrls: ['./add-student.component.css']
})
export class AddStudentComponent implements OnInit {
  public isSubmitting: any = false;
  public submitFrom: FormGroup;
  public class_list: any = [];
  public board_list: any = [];
  public subjects_list: any = [];
  public batch_list: any = [];
  constructor(private location: Location,
    public router: Router,
    public fb: FormBuilder,
    public adminService: AdminService,) { 
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
      console.log(this.subjects_list);
    }, err => {
      console.log(err.message);
    });
  }
  getBatchList(){
    this.adminService.getBatchesList().subscribe((response: any) => {
      this.batch_list = response.data;
      console.log(this.batch_list);
    }, err => {
      console.log(err.message);
    });
  }
  getBoardList(){
    this.adminService.getBoardsList().subscribe((response: any) => {
      this.board_list = response.data;
      console.log(this.board_list);
    }, err => {
      console.log(err.message);
    });
  }

  goToBack(){
    this.location.back();
}
}
