import { useState, useEffect } from 'react';
import { Alert, Text, TouchableOpacity, View, ActivityIndicator, ImageBackground, StyleSheet } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useDispatch, useSelector } from 'react-redux';

import CustomButton from '../../components/CustomButton';
import CustomTextInput from '../../components/CustomTextInput';
import { ROUTES } from '../../utils';
import { REGISTER_REQUEST } from '../../app/reducers/authReducer';

const Register = () => {
  const [emailAdd, setEmailAdd] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  const navigation = useNavigation();
  const dispatch = useDispatch();
  const { isLoading, error, registerSuccess } = useSelector(state => state.auth || {});

  // Log when Register screen loads
  console.log('[SCREEN] Register screen loaded');

  // Handle registration success
  useEffect(() => {
    if (registerSuccess) {
      console.log('[SUCCESS] Registration successful, redirecting to login');
      Alert.alert('Success', 'Registration successful! Please login.');
      navigation.navigate(ROUTES.LOGIN);
    }
  }, [registerSuccess, navigation]);

  // Handle registration error
  useEffect(() => {
    if (error) {
      console.log(`[ERROR] Registration failed: ${error}`);
      Alert.alert('Registration Failed', error);
    }
  }, [error]);

  const handleRegister = () => {
    // Log button press with final values
    console.log('[ACTION] Register button pressed');
    console.log(`[DATA] Email: ${emailAdd}, Password entered: ${password ? 'Yes' : 'No'}`);

    // Validate inputs
    if (emailAdd === '' || password === '' || confirmPassword === '') {
      console.log('[VALIDATION] Empty fields detected');
      Alert.alert('Error', 'Please fill in all fields');
      return;
    }

    if (password !== confirmPassword) {
      console.log('[VALIDATION] Passwords do not match');
      Alert.alert('Error', 'Passwords do not match');
      return;
    }

    console.log('[VALIDATION] All fields valid, dispatching REGISTER_REQUEST');
    
    // Dispatch Redux action
    dispatch({ 
      type: REGISTER_REQUEST, 
      payload: { email: emailAdd, password } 
    });
  };

  const handleLoginPress = () => {
    console.log('[ACTION] Login link pressed');
    navigation.navigate(ROUTES.LOGIN);
  };

  return (
    <ImageBackground
      source={{ uri: 'https://i.imgur.com/4NJl8sD.jpg' }}
      style={styles.background}
    >
      <View style={styles.overlay} />
      <View style={styles.formWrapper}>
        <Text style={styles.title}>Create an account</Text>
        <Text style={styles.subtitle}>Join us to pamper your furry friends</Text>

        <CustomTextInput
          label={'Email Address'}
          placeholder={'Enter Email Address'}
          value={emailAdd}
          onChangeText={setEmailAdd}
          keyboardType="email-address"
          autoCapitalize="none"
          containerStyle={styles.inputContainer}
          textStyle={styles.inputText}
        />
        <CustomTextInput
          label={'Password'}
          placeholder={'Enter Password'}
          value={password}
          onChangeText={setPassword}
          secureTextEntry={true}
          containerStyle={styles.inputContainer}
          textStyle={styles.inputText}
        />
        <CustomTextInput
          label={'Confirm Password'}
          placeholder={'Confirm Password'}
          value={confirmPassword}
          onChangeText={setConfirmPassword}
          secureTextEntry={true}
          containerStyle={styles.inputContainer}
          textStyle={styles.inputText}
        />

        <CustomButton
          label={isLoading ? "REGISTERING..." : "Sign Up"}
          containerStyle={[styles.button, isLoading && { backgroundColor: 'gray' }]}
          textStyle={styles.buttonText}
          onPress={handleRegister}
          disabled={isLoading}
        >
          {isLoading && <ActivityIndicator color="white" />}
        </CustomButton>

        <View style={styles.footerLinks}>
          <Text style={styles.footerText}>Already have an account?</Text>
          <TouchableOpacity onPress={handleLoginPress}>
            <Text style={styles.linkText}>Login</Text>
          </TouchableOpacity>
        </View>
      </View>
    </ImageBackground>
  );
};


const styles = {
  background: {
    flex: 1,
    width: '100%',
    height: '100%',
    resizeMode: 'cover',
  },
  overlay: {
    ...StyleSheet.absoluteFillObject,
    backgroundColor: 'rgba(0,0,0,0.5)',
  },
  formWrapper: {
    flex: 1,
    justifyContent: 'center',
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#fff',
    textAlign: 'center',
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 14,
    color: '#ddd',
    textAlign: 'center',
    marginBottom: 20,
  },
  inputContainer: {
    backgroundColor: '#fff',
    borderRadius: 8,
    padding: 10,
    marginBottom: 15,
  },
  inputText: {
    color: '#000',
  },
  button: {
    backgroundColor: '#28a745',
    borderRadius: 8,
    paddingVertical: 12,
    marginTop: 10,
  },
  buttonText: {
    color: '#fff',
    fontWeight: 'bold',
    textAlign: 'center',
  },
  footerLinks: {
    flexDirection: 'row',
    justifyContent: 'center',
    marginTop: 20,
  },
  footerText: {
    color: '#fff',
    marginRight: 5,
  },
  linkText: {
    color: '#ffcc00',
    textDecorationLine: 'underline',
  },
};

export default Register;