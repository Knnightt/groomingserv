import { useState, useEffect } from 'react';
import {
  Alert,
  Text,
  TouchableOpacity,
  View,
  Image,
  ScrollView,
  Dimensions,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useDispatch, useSelector } from 'react-redux';

import CustomButton from '../../components/CustomButton';
import CustomTextInput from '../../components/CustomTextInput';
import SocialButton from '../../components/SocialButton';
import { ROUTES } from '../../utils';
import { LOGIN_REQUEST } from '../../app/reducers/authReducer';

const { width, height } = Dimensions.get('window');
const isTablet = width >= 768;

const Login = () => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const navigation = useNavigation();
  const dispatch = useDispatch();

  const { isLoading, error, isAuthenticated } = useSelector(
    (state) => state.auth
  );

  useEffect(() => {
    if (error) {
      Alert.alert('Login Failed', error);
    }
  }, [error]);

  useEffect(() => {
    if (isAuthenticated) {
      navigation.replace(ROUTES.DASHBOARD);
    }
  }, [isAuthenticated, navigation]);

  const handleLogin = () => {
    if (username === '' || password === '') {
      Alert.alert(
        'Invalid Credentials',
        'Please enter your username and password'
      );
      return;
    }

    dispatch({
      type: LOGIN_REQUEST,
      payload: { email: username, password },
    });
  };

  const handleRegisterPress = () => {
    navigation.navigate(ROUTES.REGISTER);
  };

  const handleForgotPassword = () => {
    Alert.alert('Forgot Password', 'Password reset feature coming soon!');
  };

  const handleSocialLogin = (provider) => {
    Alert.alert('Social Login', `${provider} login coming soon!`);
  };

  // Testimonial Panel Component
  const TestimonialPanel = () => (
    <View style={styles.testimonialPanel}>
      <Image
        source={{
          uri: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&q=80',
        }}
        style={styles.testimonialImage}
        resizeMode="cover"
      />
      <View style={styles.testimonialOverlay} />
      <View style={styles.testimonialContent}>
        <Text style={styles.quoteIcon}>"</Text>
        <Text style={styles.testimonialText}>
          "Quibot Grooming services has transformed how I care for my pets. The
          booking is seamless and the staff is incredible."
        </Text>
        <View style={styles.testimonialAuthor}>
          <View style={styles.authorAvatar}>
            <Text style={styles.authorInitials}>KQ</Text>
          </View>
          <View>
            <Text style={styles.authorName}>Kent Quibot</Text>
            <Text style={styles.authorTitle}>Long-time Customer</Text>
          </View>
        </View>
      </View>
    </View>
  );

  // Form Panel Component
  const FormPanel = () => (
    <ScrollView
      style={styles.formPanel}
      contentContainerStyle={styles.formContent}
      showsVerticalScrollIndicator={false}
    >
      {/* Logo */}
      <View style={styles.logoContainer}>
        <View style={styles.logoCircle}>
          <Text style={styles.logoText}>QGS</Text>
        </View>
        <Text style={styles.brandName}>Quibot's Grooming Services</Text>
      </View>

      {/* Header */}
      <Text style={styles.title}>Welcome back</Text>
      <Text style={styles.subtitle}>
        Enter your details to access your account
      </Text>

      {/* Form */}
      <View style={styles.formFields}>
        <CustomTextInput
          label="Username"
          placeholder="User name"
          value={username}
          onChangeText={setUsername}
        />

        <View style={styles.passwordHeader}>
          <Text style={styles.inputLabel}>Password</Text>
          <TouchableOpacity onPress={handleForgotPassword}>
            <Text style={styles.forgotPassword}>Forgot password?</Text>
          </TouchableOpacity>
        </View>
        <CustomTextInput
          placeholder="Enter password"
          value={password}
          onChangeText={setPassword}
          secureTextEntry={true}
          containerStyle={{ marginBottom: 24, marginTop: -12 }}
        />

        <CustomButton
          label="Sign In"
          onPress={handleLogin}
          loading={isLoading}
          disabled={isLoading}
          containerStyle={{ marginBottom: 24 }}
        />
      </View>

      {/* Divider */}
      <View style={styles.divider}>
        <View style={styles.dividerLine} />
        <Text style={styles.dividerText}>OR CONTINUE WITH</Text>
        <View style={styles.dividerLine} />
      </View>

      {/* Social Buttons */}
      <View style={styles.socialButtons}>
        <SocialButton
          provider="google"
          onPress={() => handleSocialLogin('Google')}
        />
        <SocialButton
          provider="facebook"
          onPress={() => handleSocialLogin('Facebook')}
        />
      </View>

      {/* Footer Link */}
      <View style={styles.footerLinks}>
        <Text style={styles.footerText}>Don't have an account? </Text>
        <TouchableOpacity onPress={handleRegisterPress}>
          <Text style={styles.linkText}>Sign up</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
    >
      <View style={styles.mainContainer}>
        <FormPanel />
        {isTablet && <TestimonialPanel />}
      </View>
    </KeyboardAvoidingView>
  );
};

const styles = {
  container: {
    flex: 1,
    backgroundColor: '#FFFFFF',
  },
  mainContainer: {
    flex: 1,
    flexDirection: 'row',
  },
  // Form Panel Styles
  formPanel: {
    flex: 1,
    backgroundColor: '#FFFFFF',
  },
  formContent: {
    flexGrow: 1,
    justifyContent: 'center',
    paddingHorizontal: isTablet ? 60 : 24,
    paddingVertical: 40,
  },
  logoContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 40,
  },
  logoCircle: {
    width: 48,
    height: 48,
    borderRadius: 24,
    borderWidth: 2,
    borderColor: '#6B8BB8',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  logoText: {
    fontSize: 12,
    fontWeight: '600',
    color: '#6B8BB8',
  },
  brandName: {
    fontSize: 18,
    fontWeight: '600',
    color: '#6B8BB8',
  },
  title: {
    fontSize: 32,
    fontWeight: '600',
    color: '#1A1A1A',
    textAlign: 'center',
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 16,
    color: '#A8C8E8',
    textAlign: 'center',
    marginBottom: 32,
  },
  formFields: {
    marginBottom: 24,
  },
  passwordHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  inputLabel: {
    fontSize: 14,
    fontWeight: '500',
    color: '#333333',
  },
  forgotPassword: {
    fontSize: 14,
    color: '#A8C8E8',
  },
  divider: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 24,
  },
  dividerLine: {
    flex: 1,
    height: 1,
    backgroundColor: '#E5E5E5',
  },
  dividerText: {
    paddingHorizontal: 16,
    fontSize: 12,
    color: '#666666',
    fontWeight: '500',
  },
  socialButtons: {
    flexDirection: 'row',
    marginBottom: 32,
  },
  footerLinks: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
  },
  footerText: {
    fontSize: 14,
    color: '#333333',
  },
  linkText: {
    fontSize: 14,
    color: '#A8C8E8',
    fontWeight: '500',
  },
  // Testimonial Panel Styles
  testimonialPanel: {
    flex: 1,
    position: 'relative',
    overflow: 'hidden',
  },
  testimonialImage: {
    width: '100%',
    height: '100%',
    position: 'absolute',
  },
  testimonialOverlay: {
    ...Platform.select({
      ios: {
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        backgroundColor: 'rgba(107, 139, 184, 0.2)',
      },
      android: {
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        backgroundColor: 'rgba(107, 139, 184, 0.2)',
      },
    }),
  },
  testimonialContent: {
    position: 'absolute',
    bottom: 80,
    left: 40,
    right: 40,
    backgroundColor: 'rgba(255, 255, 255, 0.9)',
    borderRadius: 16,
    padding: 24,
  },
  quoteIcon: {
    fontSize: 48,
    color: '#6B8BB8',
    lineHeight: 40,
    marginBottom: 8,
  },
  testimonialText: {
    fontSize: 18,
    fontWeight: '500',
    color: '#1A1A1A',
    lineHeight: 28,
    marginBottom: 20,
  },
  testimonialAuthor: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  authorAvatar: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: '#6B8BB8',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  authorInitials: {
    fontSize: 14,
    fontWeight: '600',
    color: '#FFFFFF',
  },
  authorName: {
    fontSize: 14,
    fontWeight: '600',
    color: '#1A1A1A',
  },
  authorTitle: {
    fontSize: 12,
    color: '#666666',
  },
};

export default Login;
