import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RealEstateTwoTemplateComponent } from './real-estate-two-template.component';

describe('RealEstateTwoTemplateComponent', () => {
  let component: RealEstateTwoTemplateComponent;
  let fixture: ComponentFixture<RealEstateTwoTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RealEstateTwoTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RealEstateTwoTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
