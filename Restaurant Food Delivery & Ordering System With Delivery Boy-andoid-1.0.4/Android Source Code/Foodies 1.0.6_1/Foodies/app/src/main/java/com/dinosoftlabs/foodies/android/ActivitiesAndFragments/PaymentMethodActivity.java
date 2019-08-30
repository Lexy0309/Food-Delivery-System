package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.app.DatePickerDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;

import com.dinosoftlabs.foodies.android.R;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;

public class PaymentMethodActivity extends AppCompatActivity {

    private Button cancle_payment_method_btn,cancle_credit_card_btn;
    private RelativeLayout add_payment_method_div;

    private LinearLayout select_payment_method_layout,add_card_detail_layout;

    private EditText card_number_editText,card_validity;

    private Calendar myCalendar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_payment_method);
        myCalendar = Calendar.getInstance();
        initUI();
    }

    private void initUI(){

        cancle_payment_method_btn = findViewById(R.id.cancle_payment_method_btn);
        cancle_payment_method_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
        cancle_credit_card_btn = findViewById(R.id.cancle_credit_card_btn);
        add_card_detail_layout = findViewById(R.id.add_card_detail_layout);
        select_payment_method_layout = findViewById(R.id.select_payment_method_layout);
        add_payment_method_div = findViewById(R.id.add_payment_method_div);
        card_number_editText = findViewById(R.id.card_number_editText);
        card_validity = findViewById(R.id.card_validity);
        datePickerDialog();

        add_payment_method_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                select_payment_method_layout.setVisibility(View.GONE);
                add_card_detail_layout.setVisibility(View.VISIBLE);

            }
        });

        cancle_credit_card_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                select_payment_method_layout.setVisibility(View.VISIBLE);
                add_card_detail_layout.setVisibility(View.GONE);
            }
        });

        card_number_editText.addTextChangedListener(new FourDigitCardFormatWatcher());



    }



    public static class FourDigitCardFormatWatcher implements TextWatcher {

        // Change this to what you want... ' ', '-' etc..
        private static final char space = ' ';

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
        }

        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {
        }

        @Override
        public void afterTextChanged(Editable s) {
            // Remove spacing char
            if (s.length() > 0 && (s.length() % 5) == 0) {
                final char c = s.charAt(s.length() - 1);
                if (space == c) {
                    s.delete(s.length() - 1, s.length());
                }
            }
            // Insert char where needed.
            if (s.length() > 0 && (s.length() % 5) == 0) {
                char c = s.charAt(s.length() - 1);
                // Only if its a digit where there should be a space we insert a space
                if (Character.isDigit(c) && TextUtils.split(s.toString(), String.valueOf(space)).length <= 3) {
                    s.insert(s.length() - 1, String.valueOf(space));
                }
            }
        }
    }

    private void datePickerDialog(){


        final DatePickerDialog.OnDateSetListener date = new DatePickerDialog.OnDateSetListener() {

            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear,
                                  int dayOfMonth) {
                // TODO Auto-generated method stub
                myCalendar.set(Calendar.YEAR, year);
                myCalendar.set(Calendar.MONTH, monthOfYear);
                myCalendar.set(Calendar.DAY_OF_MONTH, dayOfMonth);
                updateLabel();
            }

        };

        card_validity.setInputType(0);
        card_validity.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                new DatePickerDialog(PaymentMethodActivity.this, date, myCalendar
                        .get(Calendar.YEAR), myCalendar.get(Calendar.MONTH),
                        myCalendar.get(Calendar.DAY_OF_MONTH)).show();
            }
        });

    }

    private void updateLabel() {
        String myFormat = "dd/yy"; //In which you need put here
        SimpleDateFormat sdf = new SimpleDateFormat(myFormat, Locale.US);

        card_validity.setText(sdf.format(myCalendar.getTime()));
    }

}
