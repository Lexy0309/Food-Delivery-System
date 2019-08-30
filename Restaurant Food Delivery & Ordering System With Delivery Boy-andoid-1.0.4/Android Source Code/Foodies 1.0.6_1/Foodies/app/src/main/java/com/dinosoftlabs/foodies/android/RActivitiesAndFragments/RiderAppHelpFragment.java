package com.dinosoftlabs.foodies.android.RActivitiesAndFragments;


import android.app.ProgressDialog;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.Toast;

import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HProfileFragment;

import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

/**
 * Created by Nabeel on 1/17/2018.
 */

public class RiderAppHelpFragment extends Fragment {

    WebView mWebview;
    ImageView close_icon;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.rider_app_help_fragment, container, false);
        // profile_pref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        FrameLayout frameLayout = v.findViewById(R.id.weekly_earning_main_container);
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);
        init(v);

        return v;
    }

    public void init(View v){

        callWebView(v);
        close_icon= v.findViewById(R.id.close_btn);
        close_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if(HProfileFragment.FLAG_ADMIN){
                    HProfileFragment rJobsFragment = new HProfileFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.weekly_earning_main_container, rJobsFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    HProfileFragment.FLAG_ADMIN = false;
                }
                else {
                    RProfileFragment rJobsFragment = new RProfileFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.weekly_earning_main_container, rJobsFragment);
                    transaction.addToBackStack(null);
                    transaction.commit();
                }
            }
        });

    }

    public void callWebView(View v) {
        final ProgressDialog pd = ProgressDialog.show(getContext(), "", "Please wait, your transaction is being processed...", true);
        mWebview = v.findViewById(R.id.web_view);
        mWebview.getSettings().setJavaScriptEnabled(true);

        mWebview.getSettings().setLoadWithOverviewMode(true);
        mWebview.getSettings().setUseWideViewPort(true);
        mWebview.getSettings().setBuiltInZoomControls(true);

        mWebview.setWebViewClient(new WebViewClient() {
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                Toast.makeText(getContext(), description, Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                pd.show();
            }


            @Override
            public void onPageFinished(WebView view, String url) {
                pd.dismiss();

                String webUrl = mWebview.getUrl();

            }


        });
        if(HProfileFragment.FLAG_ADMIN){
            mWebview.loadUrl("https://dinosoftlabs.com/foodies/restaurants/dashboard.php?p=appHelp&device=app");
           // HProfileFragment.FLAG_ADMIN = false;
        }
        mWebview.loadUrl("https://dinosoftlabs.com/foodies/courier/dashboard.php?p=appHelp&device=app");
    }
}
