import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AutomotiveTemplateComponent } from './automotive-template.component';

describe('AutomotiveTemplateComponent', () => {
  let component: AutomotiveTemplateComponent;
  let fixture: ComponentFixture<AutomotiveTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AutomotiveTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AutomotiveTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
