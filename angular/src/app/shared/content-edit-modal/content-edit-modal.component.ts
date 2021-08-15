import { Output, Input, Component, OnInit, ViewChild, Inject, Renderer2, EventEmitter, TemplateRef } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { FormBuilder, FormGroup, FormControl, Validators, FormArray } from '@angular/forms';
import { Subject } from 'rxjs/Subject'
import { UserService } from '../../core';
@Component({
  selector: 'app-content-edit-modal',
  templateUrl: './content-edit-modal.component.html',
  styleUrls: ['./content-edit-modal.component.css']
})

export class ContentEditModalComponent implements OnInit {

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

  @Output() modalClose: EventEmitter<any> = new EventEmitter();
  value: any;
  mobNumberPattern = "^((\\+91-?)|0)?[0-9]{10}$";
  public field_text: any;
  public field: any;
  public form_name: any;
  public field_value: any = '';
  public field_a_link: any = '';
  public field_a_target: any = '';
  public alerts: any = [];
  public isSubmitting = false;
  public onClose: Subject<any>;
  constructor(
    public userService: UserService,
    private fb: FormBuilder,
    private router: Router,
    public route: ActivatedRoute,
    public _bsModalRef: BsModalRef
  ) {}


  ngOnInit() {
    this.field = this.value;
    if (this.field.type === 'link') {
      this.field_value = this.field.value;
      this.field_a_link = this.field.link;
      this.field_a_target = this.field.target;
    } else {
      this.field_value = this.field.value;
    }

  }

  submitForm() {
    if (this.field.type === 'link') {
      this.field.value = this.field_value;
      this.field.link = this.field_a_link;
      this.field.target = this.field_a_target;
    } else {
      this.field.value = this.field_value;
    }
    this.modalClose.emit(this.field);
    this.hideModal('UPDATE');
  }

  hideModal(type) {
    if (type === 'UPDATE') {
      this.modalClose.emit(this.field);
    } else {
      this.modalClose.emit();
    }
    this._bsModalRef.hide();
  }
  
  ngOnDestroy(): void{
    this.alerts = [];
  }
}