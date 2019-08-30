package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.Manifest;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.content.ContextCompat;
import android.support.v7.app.AlertDialog;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.facebook.login.LoginManager;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

import static android.content.Context.MODE_PRIVATE;
import static com.dinosoftlabs.foodies.android.ActivitiesAndFragments.DealOrderFragment.DEAL_ADDRESS;
import static com.dinosoftlabs.foodies.android.ActivitiesAndFragments.DealOrderFragment.DEAL_PAYMENT_METHOD;

/**
 * Created by Nabeel on 12/12/2017.
 */

public class UserAccountFragment extends Fragment {

    ImageView back_icon_user_account;
    public static boolean FLAG_CART_USER_FRAGMENT;
    private LinearLayout user_login_div,user_not_login_div;
    private RelativeLayout user_log_out_div,user_sign_in_div,change_password_div,user_name_div,payment_method_div,
            dilervery_address_div,favourites_div,sign_up_div,terms_condition_div,terms_div,uan_div,uan_div2;
    SharedPreferences sharedPreferences;
    private TextView user_name;
    FrameLayout user_frag_main_container;

    public static boolean LOG_IN_FLAG,LOG_OUT_FLAG,FLAG_DELIVER_ADDRESS;


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.user_account_fragment, container, false);

        sharedPreferences = getContext().getSharedPreferences(PreferenceClass.user,MODE_PRIVATE);
        initUI(view);
        checkLogInSession();
        return view;
    }

    @Override
    public void onResume() {
        super.onResume();
        checkLogInSession();
    }

    private void initUI(View v){
        uan_div = v.findViewById(R.id.uan_div);
        uan_div2 = v.findViewById(R.id.uan_div2);
        terms_div = v.findViewById(R.id.terms_div);
        terms_condition_div = v.findViewById(R.id.terms_condition_div);
        back_icon_user_account = v.findViewById(R.id.back_icon_user_account);
        user_frag_main_container = v.findViewById(R.id.user_frag_main_container);
        FontHelper.applyFont(getContext(),user_frag_main_container, AllConstants.verdana);
        user_frag_main_container.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });


        if(CartFragment.CART_ADDRESS||CartFragment.CART_PAYMENT_METHOD||DEAL_ADDRESS||DEAL_PAYMENT_METHOD){
            back_icon_user_account.setVisibility(View.VISIBLE);

            back_icon_user_account.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {

                    if(DEAL_ADDRESS||DEAL_PAYMENT_METHOD){
                        DealOrderFragment rJobsFragment = new DealOrderFragment();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                        transaction.replace(R.id.user_frag_main_container, rJobsFragment);
                        transaction.addToBackStack(null);
                        transaction.commit();
                        DEAL_ADDRESS = false;
                        DEAL_PAYMENT_METHOD = false;
                    }
                    else {
                        CartFragment rJobsFragment = new CartFragment();
                        FragmentTransaction transaction = getFragmentManager().beginTransaction();
                        transaction.replace(R.id.user_frag_main_container, rJobsFragment);
                        transaction.addToBackStack(null);
                        transaction.commit();
                        CartFragment.CART_ADDRESS = false;
                        CartFragment.CART_PAYMENT_METHOD = false;
                    }
                }
            });
        }


        user_name = v.findViewById(R.id.user_name);
        String first_name = sharedPreferences.getString(PreferenceClass.pre_first, "");
        String last_name = sharedPreferences.getString(PreferenceClass.pre_last,"");

        user_name.setText(first_name + " "+ last_name);
        dilervery_address_div = v.findViewById(R.id.dilervery_address_div);
        user_login_div = v.findViewById(R.id.user_log_in_div);
        user_not_login_div = v.findViewById(R.id.user_not_log_in_div);
        user_log_out_div = v.findViewById(R.id.log_out_div);
        user_sign_in_div = v.findViewById(R.id.sign_in_div);
        change_password_div = v.findViewById(R.id.change_password_div);
        user_name_div = v.findViewById(R.id.user_name_div);
        payment_method_div= v.findViewById(R.id.payment_method_div);
        favourites_div = v.findViewById(R.id.favourites_div);
        sign_up_div = v.findViewById(R.id.sign_up_div);

        sign_up_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new SingUpActivity();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"parent").commit();

                LOG_OUT_FLAG = true;
            }
        });

        favourites_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new ShowFavoriteRestFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"parent").commit();
            }
        });


        dilervery_address_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new AddressListFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"parent").commit();
                FLAG_DELIVER_ADDRESS = true;
            }
        });

        payment_method_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new AddPaymentFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"parent").commit();
                FLAG_CART_USER_FRAGMENT = true;
            }
        });

        user_name_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new EditAccountFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"parent").commit();
                FLAG_CART_USER_FRAGMENT = true;
            }
        });

        change_password_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new ChangePasswordFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
                FLAG_CART_USER_FRAGMENT = true;
            }
        });

        user_sign_in_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new LoginAcitvity();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
               LOG_IN_FLAG = true;
            }
        });

        terms_condition_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Fragment restaurantMenuItemsFragment = new PrivacyPolicyFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
            }
        });
        terms_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Fragment restaurantMenuItemsFragment = new PrivacyPolicyFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
            }
        });

        uan_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showDialog();
            }
        });
        uan_div2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showDialog();
            }
        });

        user_log_out_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                logOutUser();
            }
        });


      /*  cart_icon = v.findViewById(R.id.order_place);
        cart_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Fragment restaurantMenuItemsFragment = new CartReportFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.user_frag_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
                FLAG_CART_USER_FRAGMENT = true;
            }
        });*/

    }

    private void checkLogInSession(){
        boolean getLoINSession = sharedPreferences.getBoolean(PreferenceClass.IS_LOGIN,false);
        if(getLoINSession){
            user_login_div.setVisibility(View.VISIBLE);
            user_not_login_div.setVisibility(View.GONE);
        }
        else {
            user_not_login_div.setVisibility(View.VISIBLE);
            user_login_div.setVisibility(View.GONE);
        }

    }

    private void logOutUser(){
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(PreferenceClass.pre_email, "");
        editor.putString(PreferenceClass.pre_pass, "");
        editor.putString(PreferenceClass.pre_first, "");
        editor.putString(PreferenceClass.pre_last, "");
        editor.putString(PreferenceClass.pre_contact, "");
        editor.putString(PreferenceClass.pre_user_id, "");
        editor.putString(PreferenceClass.ADMIN_USER_ID,"");
        editor.putBoolean(PreferenceClass.IS_LOGIN, false);
        editor.commit();

        user_not_login_div.setVisibility(View.VISIBLE);
        user_login_div.setVisibility(View.GONE);
        PagerMainActivity.viewPager.setCurrentItem(0);
        LoginManager.getInstance().logOut();

    }


    public void showDialog(){

        AlertDialog.Builder builder1 = new AlertDialog.Builder(getContext());
        builder1.setMessage("Call Us");
        builder1.setTitle("Press cal to dial our UAN number.");

        builder1.setPositiveButton(
                "Call",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        onCall();
                        dialog.cancel();
                    }
                });

        builder1.setNegativeButton(
                "Cancel",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {

                        dialog.dismiss();
                    }
                });

        AlertDialog alert11 = builder1.create();
        alert11.show();

    }


    public void phoneCall() {

        AlertDialog.Builder builder1 = new AlertDialog.Builder(getContext());
        builder1.setMessage("You must first call or chat with us.");
        builder1.setTitle("Make a cal!");
        builder1.setCancelable(true);

        builder1.setPositiveButton(
                "Call",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {



                        dialog.cancel();
                    }
                });

        builder1.setNegativeButton(
                "Cancle",
                new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                        dialog.cancel();
                    }
                });

        AlertDialog alert11 = builder1.create();
        alert11.show();

    }

    public void onCall() {
        int permissionCheck = ContextCompat.checkSelfPermission(getContext(), Manifest.permission.CALL_PHONE);

        if (permissionCheck != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(
                    getActivity(),
                    new String[]{Manifest.permission.CALL_PHONE},
                    123);
        } else {


            startActivity(new Intent(Intent.ACTION_CALL).setData(Uri.parse("tel:"+getContext().getString(R.string.uan))));
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        switch (requestCode) {

            case 123:
                if ((grantResults.length > 0) && (grantResults[0] == PackageManager.PERMISSION_GRANTED)) {
                    onCall();
                } else {
                    Log.d("TAG", "Call Permission Not Granted");
                }
                break;

            default:
                break;
        }
    }


    @Override
    public void onActivityCreated(Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        final InputMethodManager imm = (InputMethodManager) getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(getView().getWindowToken(), 0);
    }
}
