import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RetailTemplateComponent } from './retail-template.component';

describe('RetailTemplateComponent', () => {
  let component: RetailTemplateComponent;
  let fixture: ComponentFixture<RetailTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RetailTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RetailTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
